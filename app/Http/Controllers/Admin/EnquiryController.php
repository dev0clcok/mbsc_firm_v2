<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\EnquiryNote;
use App\Models\User;
use App\Support\Phone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EnquiryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:enquiries.list', only: ['index', 'show', 'export']),
            new Middleware('permission:enquiries.update', only: ['update', 'storeNote']),
            new Middleware('permission:enquiries.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): Response
    {
        $request->validate([
            'status' => ['nullable', Rule::in(Enquiry::STATUSES)],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date'],
            'sort' => ['nullable', Rule::in(['newest', 'oldest'])],
        ]);

        $enquiries = $this->query($request)
            ->with('assignee:id,name')
            ->withCount('notes')
            ->paginate(config('app.settings.pagination.per_page'))
            ->withQueryString();

        // Tab counts respect the search and dates, but not the status tab itself.
        $counts = Enquiry::query()->filtered($request)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return Inertia::render('admin/Enquiries/Index', [
            'enquiries' => $enquiries,
            'statuses' => Enquiry::STATUSES,
            'counts' => [
                'all' => $counts->sum(),
                ...collect(Enquiry::STATUSES)->mapWithKeys(fn ($status) => [$status => (int) ($counts[$status] ?? 0)]),
            ],
            // Cast so an empty set reaches the page as {} rather than [].
            'filters' => (object) $request->only(['search', 'status', 'from', 'to', 'sort']),
        ]);
    }

    public function show(Enquiry $enquiry): Response
    {
        $enquiry->load(['assignee:id,name', 'notes.author:id,name']);

        return Inertia::render('admin/Enquiries/Show', [
            'enquiry' => [
                ...$enquiry->only(['id', 'name', 'phone', 'email', 'service', 'message', 'status', 'assigned_to', 'created_at']),
                'whatsapp_number' => $enquiry->whatsappNumber(),
                'notes' => $enquiry->notes->map(fn (EnquiryNote $note) => [
                    'id' => $note->id,
                    'body' => $note->body,
                    'author' => $note->author?->name,
                    'created_at' => $note->created_at,
                ]),
            ],
            'statuses' => Enquiry::STATUSES,
            'users' => User::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(Request $request, Enquiry $enquiry): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['sometimes', 'required', Rule::in(Enquiry::STATUSES)],
            'assigned_to' => ['sometimes', 'nullable', 'exists:users,id'],
        ]);

        $enquiry->update($validated);

        return back()->with('success', 'Enquiry updated.');
    }

    public function storeNote(Request $request, Enquiry $enquiry): RedirectResponse
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
        ], [
            'body.required' => 'Write the note before saving it.',
        ]);

        $enquiry->notes()->create([
            'user_id' => $request->user()->id,
            'body' => trim($validated['body']),
        ]);

        return back()->with('success', 'Note added.');
    }

    public function destroy(Enquiry $enquiry): RedirectResponse
    {
        $enquiry->delete();

        return redirect()->route('admin.enquiries.index')->with('success', 'Enquiry deleted successfully.');
    }

    /**
     * The list as a spreadsheet, with the same filters as the screen.
     */
    public function export(Request $request): StreamedResponse
    {
        $query = $this->query($request)->with(['assignee:id,name', 'notes.author:id,name']);

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            // Byte-order mark so Excel reads Bengali and other non-Latin text correctly.
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['Received', 'Name', 'Phone', 'Email', 'Service', 'Status', 'Assigned to', 'Message', 'Internal notes']);

            $query->chunk(200, function ($enquiries) use ($out) {
                foreach ($enquiries as $enquiry) {
                    // Oldest note first, one per line inside the cell.
                    $notes = $enquiry->notes->sortBy('created_at')->map(fn (EnquiryNote $note) => sprintf(
                        '%s %s: %s',
                        $note->created_at->timezone('Asia/Dhaka')->format('Y-m-d H:i'),
                        $note->author?->name ?? 'Former user',
                        $note->body,
                    ))->implode("\n");

                    $cells = array_map($this->safeCell(...), [
                        $enquiry->created_at->timezone('Asia/Dhaka')->format('Y-m-d H:i'),
                        $enquiry->name,
                        $enquiry->phone,
                        $enquiry->email,
                        $enquiry->service,
                        $enquiry->status,
                        $enquiry->assignee?->name,
                        $enquiry->message,
                        $notes,
                    ]);
                    $cells[2] = $this->textCell($enquiry->phone);

                    fputcsv($out, $cells);
                }
            });

            fclose($out);
        }, 'enquiries-'.now()->timezone('Asia/Dhaka')->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function query(Request $request)
    {
        return Enquiry::query()
            ->filtered($request)
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->orderBy('created_at', $request->input('sort') === 'oldest' ? 'asc' : 'desc')
            ->orderBy('id', $request->input('sort') === 'oldest' ? 'asc' : 'desc');
    }

    /**
     * A phone number as text, so a spreadsheet keeps the leading zero and
     * the plus sign. Only characters a phone number can contain are kept,
     * so the cell cannot carry a formula of the visitor's own.
     */
    private function textCell(?string $value): string
    {
        $value = preg_replace('/[^0-9+\-\s()]/', '', (string) Phone::toAscii($value));

        return $value === '' ? '' : '="'.$value.'"';
    }

    /**
     * Visitors type these values. A cell that starts with a formula
     * character would be executed by a spreadsheet, so it is neutralised.
     */
    private function safeCell(?string $value): string
    {
        $value = (string) $value;

        return preg_match('/^[=+\-@\t\r]/', $value) ? "'".$value : $value;
    }
}
