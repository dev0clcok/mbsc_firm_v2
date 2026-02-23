<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Services\FAQService;
use App\Http\Services\ServiceService;
use App\Http\Services\TeamMemberService;
use App\Http\Services\TestimonialService;
use App\Models\FAQ;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(
        TestimonialService $testimonialService,
        TeamMemberService $teamMemberService,
        ServiceService $serviceService,
        FAQService $faqService
    ): Response {
        $request = request()->merge(['status' => 1]);

        $testimonials = $testimonialService->index($request, false)
            ->map(fn(Testimonial $t) => [
                'name' => $t->name,
                'position' => $t->position,
                'company' => $t->company,
                'text' => $t->text,
                'rating' => $t->rating,
                'avatar_url' => $t->avatar_url,
            ])
            ->values();

        $teamMembers = $teamMemberService->index($request, false, 3)
            ->map(fn(TeamMember $m) => [
                'name' => $m->name,
                'position' => $m->position,
                'specialization' => $m->specialization,
                'image' => $m->image_url,
                'social_links' => $m->socialLinks->map(fn($s) => [
                    'platform' => $s->platform,
                    'url' => $s->url,
                ])->values()->all(),
            ])
            ->values();

        $services = $serviceService->index($request, false)
            ->map(fn(Service $s) => [
                'slug' => $s->slug,
                'title' => $s->title,
                'description' => $s->short_description,
                'icon' => $s->icon_svg,
            ])
            ->values();

        $faqs = $faqService->index($request, false)
            ->map(fn (FAQ $f) => [
                'question' => $f->question,
                'answer' => $f->answer,
            ])
            ->values();

        return Inertia::render('Welcome', [
            'testimonials' => $testimonials,
            'teamMembers' => $teamMembers,
            'services' => $services,
            'faqs' => $faqs,
        ]);
    }

    public function services(ServiceService $serviceService): Response
    {
        $request = request()->merge(['status' => 1]);
        $services = $serviceService->index($request, false)
            ->map(fn(Service $s) => [
                'id' => $s->slug,
                'title' => $s->title,
                'shortDescription' => $s->short_description,
                'description' => $s->description,
                'icon' => $s->icon_svg,
                'features' => $s->features ?? [],
                'image' => $s->image_url,
            ])
            ->values();

        return Inertia::render('Services', [
            'services' => $services,
        ]);
    }

    public function about(TeamMemberService $teamMemberService): Response
    {
        $request = request()->merge(['status' => 1]);
        $teamMembers = $teamMemberService->index($request, false)
            ->map(fn (TeamMember $m) => [
                'name' => $m->name,
                'position' => $m->position,
                'specialization' => $m->specialization,
                'image' => $m->image_url,
                'social_links' => $m->socialLinks->map(fn ($s) => [
                    'platform' => $s->platform,
                    'url' => $s->url,
                ])->values()->all(),
            ])
            ->values();

        return Inertia::render('About', [
            'teamMembers' => $teamMembers,
        ]);
    }

    public function contact(): Response
    {
        return Inertia::render('Contact');
    }
}
