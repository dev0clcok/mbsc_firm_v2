<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Statistic;
use App\Models\Testimonial;
use Inertia\Inertia;
use Inertia\Response;

class AboutController extends Controller
{
    public function index(): Response
    {
        $statistics = Statistic::active()
            ->orderBy('sort_order')
            ->get();

        $testimonials = Testimonial::active()
            ->featured()
            ->limit(6)
            ->get();

        return Inertia::render('public/About', [
            'statistics' => $statistics,
            'testimonials' => $testimonials,
        ]);
    }
}
