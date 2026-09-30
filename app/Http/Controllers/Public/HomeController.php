<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        $hero = SiteSetting::image('hero_home');

        return Inertia::render('Welcome', [
            'hero' => $hero,
            'services' => $this->serviceSummaries(),
            'teamMembers' => $this->teamMembers(),
            'testimonials' => Testimonial::query()->active()->orderBy('sort_order')->orderBy('id')->get()
                ->map(fn (Testimonial $t) => [
                    'name' => $t->name,
                    'position' => $t->position,
                    'company' => $t->company,
                    'text' => $t->text,
                ])
                ->values(),
            'faqs' => FAQ::query()->active()->orderBy('sort_order')->orderBy('id')->get()
                ->map(fn (FAQ $f) => [
                    'question' => $f->question,
                    'answer' => $f->answer,
                ])
                ->values(),
        ])->withViewData('seo', [
            'title' => 'Legal and tax solutions for your business',
            'description' => 'MBSC Firm handles RJSC company registration, income tax, VAT and audit support for businesses and individuals from its office in Kotowali, Chattogram.',
        ])->withViewData('preloadImage', $hero);
    }

    public function services(): Response
    {
        return Inertia::render('Services', [
            'hero' => SiteSetting::image('hero_services'),
            'services' => $this->serviceSummaries(),
        ])->withViewData('seo', [
            'title' => 'Services',
            'description' => 'RJSC company, partnership and society registration, income tax returns and appeals, VAT registration and returns, and audit support in Chattogram.',
        ]);
    }

    public function service(string $slug): Response
    {
        $service = Service::query()->active()->where('slug', $slug)->firstOrFail();

        return Inertia::render('Service', [
            'service' => [
                'slug' => $service->slug,
                'title' => $service->title,
                'summary' => $service->short_description,
                'description' => $service->description,
                'features' => $service->features ?? [],
                'icon' => $service->icon_svg,
                'image' => $service->image(),
            ],
        ])->withViewData('seo', [
            'title' => $service->title,
            'description' => $service->short_description ?: str($service->description)->limit(155)->toString(),
        ]);
    }

    public function about(): Response
    {
        return Inertia::render('About', [
            'hero' => SiteSetting::image('hero_about'),
            'teamMembers' => $this->teamMembers(),
        ])->withViewData('seo', [
            'title' => 'About the firm',
            'description' => 'MBSC Firm is a Chattogram practice for RJSC, income tax, VAT and audit support work, built on careful documentation and clear communication with regulators.',
        ]);
    }

    public function contact(): Response
    {
        return Inertia::render('Contact')->withViewData('seo', [
            'title' => 'Contact',
            'description' => 'Send an enquiry, call or message MBSC Firm on WhatsApp, or visit the office in Kotowali, Chattogram. The first consultation is free.',
        ]);
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    private function serviceSummaries(): Collection
    {
        return Service::query()->active()->orderBy('sort_order')->orderBy('id')->get()
            ->map(fn (Service $s) => [
                'slug' => $s->slug,
                'title' => $s->title,
                'summary' => $s->short_description,
                'icon' => $s->icon_svg,
                'image' => $s->image(),
            ])
            ->values();
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    private function teamMembers(): Collection
    {
        return TeamMember::query()->active()->with('socialLinks')->orderBy('sort_order')->orderBy('id')->get()
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
    }
}
