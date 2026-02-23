<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTestimonialRequest;
use App\Http\Requests\Admin\UpdateTestimonialRequest;
use App\Http\Services\TestimonialService;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Inertia\Response;

class TestimonialController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:testimonials.list', only: ['index']),
            new Middleware('permission:testimonials.create', only: ['create', 'store']),
            new Middleware('permission:testimonials.update', only: ['edit', 'update']),
            new Middleware('permission:testimonials.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request, TestimonialService $testimonialService): Response
    {
        $testimonials = $testimonialService->index($request);

        return Inertia::render('admin/Testimonials/Index', [
            'testimonials' => $testimonials,
            'filters' => [
                'search' => $request->search,
                'status' => $request->status,
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/Testimonials/Create');
    }

    public function store(StoreTestimonialRequest $request, TestimonialService $testimonialService): RedirectResponse
    {
        $testimonialService->store($request->validated(), $request);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial created successfully.');
    }

    public function edit(Testimonial $testimonial): Response
    {
        return Inertia::render('admin/Testimonials/Edit', [
            'testimonial' => $testimonial,
        ]);
    }

    public function update(UpdateTestimonialRequest $request, Testimonial $testimonial, TestimonialService $testimonialService): RedirectResponse
    {
        $testimonialService->update($testimonial, $request->validated(), $request);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial updated successfully.');
    }

    public function destroy(Testimonial $testimonial, TestimonialService $testimonialService): RedirectResponse
    {
        $testimonialService->destroy($testimonial);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial deleted successfully.');
    }
}
