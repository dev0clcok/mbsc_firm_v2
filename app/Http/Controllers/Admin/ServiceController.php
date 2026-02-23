<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreServiceRequest;
use App\Http\Requests\Admin\UpdateServiceRequest;
use App\Http\Services\ServiceService;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Inertia\Response;

class ServiceController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:services.list', only: ['index']),
            new Middleware('permission:services.create', only: ['create', 'store']),
            new Middleware('permission:services.update', only: ['edit', 'update']),
            new Middleware('permission:services.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request, ServiceService $serviceService): Response
    {
        $services = $serviceService->index($request);

        return Inertia::render('admin/Services/Index', [
            'services' => $services,
            'filters' => [
                'search' => $request->search,
                'status' => $request->status,
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/Services/Create');
    }

    public function store(StoreServiceRequest $request, ServiceService $serviceService): RedirectResponse
    {
        $serviceService->store($request->validated(), $request);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service created successfully.');
    }

    public function edit(Service $service): Response
    {
        return Inertia::render('admin/Services/Edit', [
            'service' => $service,
        ]);
    }

    public function update(UpdateServiceRequest $request, Service $service, ServiceService $serviceService): RedirectResponse
    {
        $serviceService->update($service, $request->validated(), $request);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service, ServiceService $serviceService): RedirectResponse
    {
        $serviceService->destroy($service);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service deleted successfully.');
    }
}
