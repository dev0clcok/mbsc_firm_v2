<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use App\Models\Service;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FAQController extends Controller
{
    public function index(Request $request): Response
    {
        $query = FAQ::active()
            ->with('service')
            ->orderBy('sort_order');

        if ($request->category) {
            $query->where('category', $request->category);
        }

        if ($request->service) {
            $query->where('service_id', $request->service);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('question', 'like', "%{$request->search}%")
                    ->orWhere('answer', 'like', "%{$request->search}%");
            });
        }

        $faqs = $query->get();

        // Group by category
        $faqsByCategory = $faqs->groupBy('category');

        $services = Service::parents()->active()->get();
        $categories = FAQ::active()->distinct()->pluck('category')->filter();

        return Inertia::render('public/FAQ', [
            'faqsByCategory' => $faqsByCategory,
            'services' => $services,
            'categories' => $categories,
            'filters' => [
                'category' => $request->category,
                'service' => $request->service,
                'search' => $request->search,
            ],
        ]);
    }
}
