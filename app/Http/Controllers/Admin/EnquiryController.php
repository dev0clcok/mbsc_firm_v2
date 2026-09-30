<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class EnquiryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:enquiries.list', only: ['index']),
            new Middleware('permission:enquiries.update', only: ['update']),
            new Middleware('permission:enquiries.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): Response
    {
        $query = Enquiry::query();

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $enquiries = $query->latest()
            ->paginate(config('app.settings.pagination.per_page'))
            ->withQueryString();

        return Inertia::render('admin/Enquiries/Index', [
            'enquiries' => $enquiries,
            'statuses' => Enquiry::STATUSES,
            'newCount' => Enquiry::query()->where('status', Enquiry::STATUS_NEW)->count(),
            'filters' => [
                'search' => $request->search,
                'status' => $request->status,
            ],
        ]);
    }

    public function update(Request $request, Enquiry $enquiry): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(Enquiry::STATUSES)],
        ]);

        $enquiry->update($validated);

        return back()->with('success', 'Enquiry status updated.');
    }

    public function destroy(Enquiry $enquiry): RedirectResponse
    {
        $enquiry->delete();

        return back()->with('success', 'Enquiry deleted successfully.');
    }
}
