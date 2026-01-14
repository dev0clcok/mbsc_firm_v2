<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\CaseStudy;
use App\Models\Service;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CaseStudyController extends Controller
{
    public function index(Request $request): Response
    {
        $query = CaseStudy::published()
            ->with('service');

        if ($request->service) {
            $query->where('service_id', $request->service);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                    ->orWhere('challenge', 'like', "%{$request->search}%");
            });
        }

        $caseStudies = $query->latest()->paginate(12)->withQueryString();

        $services = Service::parents()->active()->get();

        return Inertia::render('public/CaseStudies/Index', [
            'caseStudies' => $caseStudies,
            'services' => $services,
            'filters' => [
                'service' => $request->service,
                'search' => $request->search,
            ],
        ]);
    }

    public function show(CaseStudy $caseStudy): Response
    {
        if (!$caseStudy->is_published) {
            abort(404);
        }

        $relatedCaseStudies = CaseStudy::published()
            ->where('id', '!=', $caseStudy->id)
            ->where('service_id', $caseStudy->service_id)
            ->limit(3)
            ->get();

        return Inertia::render('public/CaseStudies/Show', [
            'caseStudy' => $caseStudy->load('service'),
            'relatedCaseStudies' => $relatedCaseStudies,
        ]);
    }
}
