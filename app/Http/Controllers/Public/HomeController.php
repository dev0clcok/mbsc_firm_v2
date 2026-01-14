<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Service;
use App\Models\Statistic;
use App\Models\Testimonial;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        $services = Service::parents()
            ->active()
            ->with('children')
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        $statistics = Statistic::active()
            ->orderBy('sort_order')
            ->get();

        $testimonials = Testimonial::active()
            ->featured()
            ->limit(3)
            ->get();

        $recentPosts = BlogPost::published()
            ->recent()
            ->with('category', 'author')
            ->limit(3)
            ->get();

        return Inertia::render('public/Home', [
            'services' => $services,
            'statistics' => $statistics,
            'testimonials' => $testimonials,
            'recentPosts' => $recentPosts,
        ]);
    }
}
