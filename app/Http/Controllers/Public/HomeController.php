<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Welcome');
    }

    public function services(): Response
    {
        return Inertia::render('Services');
    }

    public function about(): Response
    {
        return Inertia::render('About');
    }

        public function contact(): Response
    {
        return Inertia::render('Contact');
    }
}
