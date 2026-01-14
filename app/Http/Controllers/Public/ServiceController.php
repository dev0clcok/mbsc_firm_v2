<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ServiceController extends Controller
{
    public function index(): Response
    {
        $services = Service::parents()
            ->active()
            ->with('children')
            ->orderBy('sort_order')
            ->get();

        return Inertia::render('public/Services/Index', [
            'services' => $services,
        ]);
    }

    public function show(Service $service): Response
    {
        $service->load('parent', 'children');

        // Only show active services
        if (!$service->is_active) {
            abort(404);
        }

        $relatedServices = Service::where('parent_id', $service->parent_id)
            ->where('id', '!=', $service->id)
            ->active()
            ->limit(3)
            ->get();

        return Inertia::render('public/Services/Show', [
            'service' => $service,
            'relatedServices' => $relatedServices,
        ]);
    }

    public function showChild(Service $parent, Service $child): Response
    {
        if ($child->parent_id !== $parent->id) {
            abort(404);
        }

        if (!$parent->is_active || !$child->is_active) {
            abort(404);
        }

        return Inertia::render('public/Services/Show', [
            'service' => $child,
            'parent' => $parent,
        ]);
    }
}
