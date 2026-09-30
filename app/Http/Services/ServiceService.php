<?php

namespace App\Http\Services;

use App\Models\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ServiceService
{
    public function __construct(private ImageStore $images) {}

    /**
     * @return LengthAwarePaginator|Collection<int, Service>
     */
    public function index(Request $request, $pagination = true): LengthAwarePaginator|Collection
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

        $query = $query->orderBy('sort_order')->orderBy('id');

        if ($pagination) {
            return $query->paginate(config('app.settings.pagination.per_page'))->withQueryString();
        }

        return $query->get();
    }

    /**
     * @param array<string, mixed> $data
     */
    public function store(array $data, Request $request): Service
    {
        unset($data['image']);
        $data = $this->cleanLists($data);

        if ($request->hasFile('image')) {
            $data = [...$data, ...$this->storeImage($request)];
        }

        return Service::query()->create($data);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function update(Service $service, array $data, Request $request): Service
    {
        unset($data['image'], $data['remove_image']);
        $data = $this->cleanLists($data);

        if ($request->hasFile('image')) {
            $this->images->delete($service->image_url);
            $data = [...$data, ...$this->storeImage($request)];
        } elseif ($request->boolean('remove_image')) {
            $this->images->delete($service->image_url);
            $data = [...$data, 'image_url' => null, 'image_width' => null, 'image_height' => null, 'image_alt' => null];
        }

        $service->update($data);

        return $service;
    }

    public function destroy(Service $service): void
    {
        $this->images->delete($service->image_url);
        $service->delete();
    }

    /**
     * @return array{image_url: string, image_width: int, image_height: int}
     */
    private function storeImage(Request $request): array
    {
        $image = $this->images->store($request->file('image'), 'services');

        return [
            'image_url' => $image['url'],
            'image_width' => $image['width'],
            'image_height' => $image['height'],
        ];
    }

    /**
     * Drop blank rows that the form's repeatable lists may send.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function cleanLists(array $data): array
    {
        $lines = fn ($list) => array_values(array_filter(
            array_map(fn ($v) => is_string($v) ? trim($v) : '', (array) $list),
            fn ($v) => $v !== '',
        ));

        $data['features'] = $lines($data['features'] ?? []);
        $data['documents'] = $lines($data['documents'] ?? []);

        $data['process_steps'] = array_values(array_filter(
            array_map(fn ($step) => [
                'title' => trim((string) ($step['title'] ?? '')),
                'description' => trim((string) ($step['description'] ?? '')),
            ], (array) ($data['process_steps'] ?? [])),
            fn ($step) => $step['title'] !== '',
        ));

        return $data;
    }
}
