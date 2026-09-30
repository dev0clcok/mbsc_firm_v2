<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Inertia\Response;

class FAQController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:faqs.list', only: ['index']),
            new Middleware('permission:faqs.create', only: ['create', 'store']),
            new Middleware('permission:faqs.update', only: ['edit', 'update']),
            new Middleware('permission:faqs.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): Response
    {
        $query = FAQ::query()->with('service:id,title');

        if ($request->filled('service')) {
            $request->input('service') === 'general'
                ? $query->whereNull('service_id')
                : $query->where('service_id', $request->integer('service'));
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', "%{$search}%")
                    ->orWhere('answer', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->integer('status'));
        }

        $faqs = $query->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(config('app.settings.pagination.per_page'))
            ->withQueryString();

        return Inertia::render('admin/FAQs/Index', [
            'faqs' => $faqs,
            'services' => $this->serviceOptions(),
            'filters' => [
                'search' => $request->search,
                'status' => $request->status,
                'service' => $request->service,
            ],
        ]);
    }

    /**
     * @return \Illuminate\Support\Collection<int, array{id: int, title: string}>
     */
    private function serviceOptions()
    {
        return Service::query()->orderBy('sort_order')->get(['id', 'title'])
            ->map(fn (Service $s) => ['id' => $s->id, 'title' => $s->title]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/FAQs/Create', [
            'services' => $this->serviceOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'nullable|exists:services,id',
            'question' => 'required|string|max:1000',
            'answer' => 'required|string',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        FAQ::create($validated);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ created successfully.');
    }

    public function edit(FAQ $faq): Response
    {
        return Inertia::render('admin/FAQs/Edit', [
            'faq' => $faq,
            'services' => $this->serviceOptions(),
        ]);
    }

    public function update(Request $request, FAQ $faq)
    {
        $validated = $request->validate([
            'service_id' => 'nullable|exists:services,id',
            'question' => 'required|string|max:1000',
            'answer' => 'required|string',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $faq->update($validated);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ updated successfully.');
    }

    public function destroy(FAQ $faq)
    {
        $faq->delete();

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ deleted successfully.');
    }
}
