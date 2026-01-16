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
        // dd($request->all());
        $query = FAQ::with('service');

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->string('category'));
        }

        // Filter by service
        if ($request->filled('service')) {
            $query->where('service_id', $request->integer('service'));
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
            ->orderBy('category')
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        $services = Service::parents()
            ->active()
            ->orderBy('sort_order')
            ->get();

        $categories = FAQ::distinct()
            ->whereNotNull('category')
            ->pluck('category')
            ->filter()
            ->sort()
            ->values();

        return Inertia::render('admin/FAQs/Index', [
            'faqs' => $faqs,
            'services' => $services,
            'categories' => $categories,
            'filters' => [
                'category' => $request->category,
                'service' => $request->service,
                'search' => $request->search,
                'status' => $request->status,
            ],
        ]);
    }

    public function create(): Response
    {
        $services = Service::parents()
            ->active()
            ->orderBy('sort_order')
            ->get();

        $categories = FAQ::distinct()->pluck('category')->filter()->sort()->values();

        return Inertia::render('admin/FAQs/Create', [
            'services' => $services,
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:1000',
            'answer' => 'required|string',
            'category' => 'nullable|string|max:255',
            'service_id' => 'nullable|exists:services,id',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        FAQ::create($validated);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ created successfully.');
    }

    public function edit(FAQ $faq): Response
    {
        $faq->load('service');

        $services = Service::parents()
            ->active()
            ->orderBy('sort_order')
            ->get();

        $categories = FAQ::distinct()->pluck('category')->filter()->sort()->values();

        return Inertia::render('admin/FAQs/Edit', [
            'faq' => $faq,
            'services' => $services,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, FAQ $faq)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:1000',
            'answer' => 'required|string',
            'category' => 'nullable|string|max:255',
            'service_id' => 'nullable|exists:services,id',
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
