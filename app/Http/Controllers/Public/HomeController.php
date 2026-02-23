<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        $testimonials = Testimonial::query()
            ->active()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get(['name', 'position', 'company', 'text', 'rating', 'avatar_url'])
            ->map(fn (Testimonial $t) => [
                'name' => $t->name,
                'position' => $t->position,
                'company' => $t->company,
                'text' => $t->text,
                'rating' => $t->rating,
                'avatar_url' => $t->avatar_url,
            ])
            ->values();

        $teamMembers = TeamMember::query()
            ->active()
            ->with('socialLinks')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->limit(3)
            ->get()
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

        $services = Service::query()
            ->active()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get(['slug', 'title', 'short_description', 'icon_svg'])
            ->map(fn (Service $s) => [
                'slug' => $s->slug,
                'title' => $s->title,
                'description' => $s->short_description,
                'icon' => $s->icon_svg,
            ])
            ->values();

        return Inertia::render('Welcome', [
            'testimonials' => $testimonials,
            'teamMembers' => $teamMembers,
            'services' => $services,
        ]);
    }

    public function services(): Response
    {
        $services = Service::query()
            ->active()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get([
                'slug',
                'title',
                'short_description',
                'description',
                'icon_svg',
                'features',
                'image_url',
            ])
            ->map(fn (Service $s) => [
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

    public function about(): Response
    {
        $teamMembers = TeamMember::query()
            ->active()
            ->with('socialLinks')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
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
