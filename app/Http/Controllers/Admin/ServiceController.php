<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
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

    public function index(Request $request): Response
    {
        $query = Service::query();

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->integer('status'));
        }

        $services = $query
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(config('app.settings.pagination.per_page'))
            ->withQueryString();

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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:services,slug'],
            'title' => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:2000'],
            'description' => ['nullable', 'string'],
            'icon_svg' => ['nullable', 'string'],
            'features' => ['nullable', 'array'],
            'features.*' => ['string', 'max:255'],
            'image' => ['nullable', 'file', 'image', 'max:2048'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['boolean'],
        ]);

        $validated['features'] = array_values(array_filter(
            $validated['features'] ?? [],
            fn ($v) => is_string($v) && trim($v) !== ''
        ));

        unset($validated['image']);
        if ($request->hasFile('image')) {
            $validated['image_url'] = $request->file('image')->store('services', 'public');
            $validated['image_url'] = '/storage/'.$validated['image_url'];
        } else {
            $validated['image_url'] = null;
        }

        Service::create($validated);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service created successfully.');
    }

    public function edit(Service $service): Response
    {
        return Inertia::render('admin/Services/Edit', [
            'service' => $service,
        ]);
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:services,slug,'.$service->id],
            'title' => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:2000'],
            'description' => ['nullable', 'string'],
            'icon_svg' => ['nullable', 'string'],
            'features' => ['nullable', 'array'],
            'features.*' => ['string', 'max:255'],
            'image' => ['nullable', 'file', 'image', 'max:2048'],
            'remove_image' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['boolean'],
        ]);

        $validated['features'] = array_values(array_filter(
            $validated['features'] ?? [],
            fn ($v) => is_string($v) && trim($v) !== ''
        ));

        unset($validated['image'], $validated['remove_image']);

        if ($request->boolean('remove_image')) {
            $this->deleteStoredImage($service->image_url);
            $validated['image_url'] = null;
        } elseif ($request->hasFile('image')) {
            $this->deleteStoredImage($service->image_url);
            $validated['image_url'] = $request->file('image')->store('services', 'public');
            $validated['image_url'] = '/storage/'.$validated['image_url'];
        }

        $service->update($validated);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service updated successfully.');
    }

    private function deleteStoredImage(?string $imageUrl): void
    {
        if (! $imageUrl || ! str_starts_with($imageUrl, '/storage/')) {
            return;
        }
        $path = str_replace('/storage/', '', $imageUrl);
        Storage::disk('public')->delete($path);
    }

    public function destroy(Service $service)
    {
        $this->deleteStoredImage($service->image_url);
        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', 'Service deleted successfully.');
    }
}

