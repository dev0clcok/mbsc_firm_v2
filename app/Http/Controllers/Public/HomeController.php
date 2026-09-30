<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Services\ImageStore;
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
            // General questions only; service-specific ones live on the service pages and /faqs.
            'faqs' => FAQ::query()->active()->whereNull('service_id')->orderBy('sort_order')->orderBy('id')->limit(6)->get()
                ->map(fn (FAQ $f) => $this->faq($f))
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
                'process_steps' => $service->process_steps ?? [],
                'documents' => $service->documents ?? [],
                'timeline' => $service->timeline,
                'fees' => $service->fees,
                'icon' => $service->icon_svg,
                'image' => $service->image(),
            ],
            'faqs' => $service->faqs()->active()->get()->map(fn (FAQ $f) => $this->faq($f))->values(),
        ])->withViewData('seo', [
            'title' => $service->title,
            'description' => $service->short_description ?: str($service->description)->limit(155)->toString(),
        ]);
    }

    public function faqs(): Response
    {
        $faqs = FAQ::query()->active()->orderBy('sort_order')->orderBy('id')->get();
        $services = Service::query()->active()->orderBy('sort_order')->orderBy('id')->get(['id', 'slug', 'title']);

        $groups = collect([['title' => 'General', 'slug' => null, 'faqs' => $faqs->whereNull('service_id')]])
            ->concat($services->map(fn (Service $s) => [
                'title' => $s->title,
                'slug' => $s->slug,
                'faqs' => $faqs->where('service_id', $s->id),
            ]))
            ->filter(fn (array $group) => $group['faqs']->isNotEmpty())
            ->map(fn (array $group) => [...$group, 'faqs' => $group['faqs']->map(fn (FAQ $f) => $this->faq($f))->values()])
            ->values();

        // Only questions that are actually shown on the page go into the structured data.
        $shown = $groups->flatMap(fn (array $group) => $group['faqs'])->pluck('id');

        return Inertia::render('Faqs', [
            'groups' => $groups,
        ])->withViewData('seo', [
            'title' => 'Frequently asked questions',
            'description' => 'Answers to common questions about company registration, income tax, VAT and audit support with MBSC Firm.',
        ])->withViewData('structuredData', $shown->isEmpty() ? null : [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $faqs->whereIn('id', $shown)->map(fn (FAQ $f) => [
                '@type' => 'Question',
                'name' => $f->question,
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f->answerText()],
            ])->values()->all(),
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

    public function privacy(): Response
    {
        abort_unless(SiteSetting::privacyPublished(), 404);

        return Inertia::render('Privacy', [
            'policy' => SiteSetting::get('privacy_policy'),
            'updatedAt' => SiteSetting::query()->where('key', 'privacy_policy')->value('updated_at'),
        ])->withViewData('seo', [
            'title' => 'Privacy policy',
            'description' => 'How MBSC Firm collects, uses and protects personal information sent through this website.',
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
                'highlights' => array_slice($s->features ?? [], 0, 3),
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
                // Portraits are shown small, so the 480px file is enough.
                'image' => $m->image_url ? ImageStore::variant($m->image_url, 480) : null,
                'social_links' => $m->socialLinks->map(fn ($s) => [
                    'platform' => $s->platform,
                    'url' => $s->url,
                ])->values()->all(),
            ])
            ->values();
    }

    /**
     * @return array{id: int, question: string, answer: string}
     */
    private function faq(FAQ $faq): array
    {
        return ['id' => $faq->id, 'question' => $faq->question, 'answer' => $faq->answerHtml()];
    }
}
