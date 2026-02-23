<?php

namespace App\Http\Services;

use App\Models\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceService
{
    public function index(Request $request): LengthAwarePaginator
    {
        $query = Service::query();

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function (Builder $q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->integer('status'));
        }

        return $query
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(config('app.settings.pagination.per_page'))
            ->withQueryString();
    }

    /**
     * @param array<string, mixed> $data
     */
    public function store(array $data, Request $request): Service
    {
        unset($data['image']);

        $data['features'] = array_values(array_filter(
            $data['features'] ?? [],
            fn ($v) => is_string($v) && trim($v) !== ''
        ));

        $data['image_url'] = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('services', 'public');
            $data['image_url'] = '/storage/'.$path;
        }

        return Service::query()->create($data);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function update(Service $service, array $data, Request $request): Service
    {
        unset($data['image'], $data['remove_image']);

        $data['features'] = array_values(array_filter(
            $data['features'] ?? [],
            fn ($v) => is_string($v) && trim($v) !== ''
        ));

        if ($request->boolean('remove_image')) {
            $this->deleteStoredImage($service->image_url);
            $data['image_url'] = null;
        } elseif ($request->hasFile('image')) {
            $this->deleteStoredImage($service->image_url);
            $path = $request->file('image')->store('services', 'public');
            $data['image_url'] = '/storage/'.$path;
        }

        $service->update($data);

        return $service;
    }

    public function destroy(Service $service): void
    {
        $this->deleteStoredImage($service->image_url);
        $service->delete();
    }

    private function deleteStoredImage(?string $imageUrl): void
    {
        if (! $imageUrl || ! str_starts_with($imageUrl, '/storage/')) {
            return;
        }
        $path = str_replace('/storage/', '', $imageUrl);
        Storage::disk('public')->delete($path);
    }
}
