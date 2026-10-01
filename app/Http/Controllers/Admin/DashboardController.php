<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\FAQ;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:admin.access'),
        ];
    }

    public function __invoke(): Response
    {
        return Inertia::render('admin/Dashboard', [
            'stats' => [
                'new_enquiries' => Enquiry::query()->where('status', Enquiry::STATUS_NEW)->count(),
                'enquiries' => Enquiry::query()->count(),
                'services' => Service::query()->active()->count(),
                'team_members' => TeamMember::query()->active()->count(),
                'faqs' => FAQ::query()->active()->count(),
                'testimonials' => Testimonial::query()->active()->count(),
            ],
            'recentEnquiries' => Enquiry::query()->latest()->limit(5)
                ->get(['id', 'name', 'service', 'message', 'status', 'created_at']),
            'weeklyEnquiries' => $this->weeklyEnquiries(),
            'checklist' => $this->checklist(),
        ]);
    }

    /**
     * Enquiries received in each of the last eight weeks, oldest first.
     * A week here is seven days ending today, so the last bar is always
     * a full week rather than a part of the calendar week.
     *
     * @return array<int, array{from: string, to: string, count: int}>
     */
    private function weeklyEnquiries(): array
    {
        $today = now('Asia/Dhaka')->endOfDay();
        $start = $today->copy()->subDays(8 * 7)->addSecond();

        $dates = Enquiry::query()->where('created_at', '>=', $start->copy()->utc())->pluck('created_at');

        return collect(range(0, 7))->map(function (int $week) use ($start, $dates) {
            $from = $start->copy()->addDays($week * 7);
            $to = $from->copy()->addDays(7)->subSecond();

            return [
                'from' => $from->toDateString(),
                'to' => $to->toDateString(),
                'count' => $dates->filter(fn ($date) => $date->between($from, $to))->count(),
            ];
        })->all();
    }

    /**
     * Things the public site still needs, so the owner can see at a glance
     * what is unfinished. Each item links to where it is fixed.
     *
     * @return array<int, array{key: string, done: bool, href: string}>
     */
    private function checklist(): array
    {
        $settings = SiteSetting::values();

        return [
            ['key' => 'enquiry_email', 'done' => filled($settings['enquiry_email']), 'href' => '/admin/site-settings'],
            ['key' => 'mail', 'done' => ! in_array(config('mail.default'), ['log', 'array'], true), 'href' => '/admin/site-settings'],
            ['key' => 'maps_url', 'done' => filled($settings['maps_url']), 'href' => '/admin/site-settings'],
            ['key' => 'hero_images', 'done' => collect(SiteSetting::IMAGE_KEYS)->every(fn ($key) => SiteSetting::image($key) !== null), 'href' => '/admin/site-settings'],
            ['key' => 'testimonials', 'done' => Testimonial::query()->active()->exists(), 'href' => '/admin/testimonials'],
        ];
    }
}
