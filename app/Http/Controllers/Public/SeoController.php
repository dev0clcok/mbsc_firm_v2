<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function sitemap(): Response
    {
        $services = Service::query()->active()->orderBy('sort_order')->get(['slug', 'updated_at']);

        $urls = collect([
            ['loc' => route('home'), 'lastmod' => null],
            ['loc' => route('services'), 'lastmod' => $services->max('updated_at')],
            ['loc' => route('faqs'), 'lastmod' => null],
            ['loc' => route('about'), 'lastmod' => null],
            ['loc' => route('contact'), 'lastmod' => null],
        ])->concat($services->map(fn (Service $s) => [
            'loc' => route('services.show', $s->slug),
            'lastmod' => $s->updated_at,
        ]));

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            'Disallow: /admin',
            'Disallow: /settings',
            'Disallow: /login',
            'Disallow: /register',
            '',
            'Sitemap: '.route('sitemap'),
        ];

        return response(implode("\n", $lines)."\n")->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
