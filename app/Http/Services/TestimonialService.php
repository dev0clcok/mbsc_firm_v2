<?php

namespace App\Http\Services;

use App\Models\Testimonial;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimonialService
{
    public function index(Request $request): LengthAwarePaginator
    {
        $query = Testimonial::query();

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%")
                    ->orWhere('text', 'like', "%{$search}%");
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
    public function store(array $data, Request $request): Testimonial
    {
        unset($data['avatar']);

        $data['avatar_url'] = null;
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('testimonials', 'public');
            $data['avatar_url'] = '/storage/'.$path;
        }

        $data['rating'] = $data['rating'] ?? 5;

        return Testimonial::query()->create($data);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function update(Testimonial $testimonial, array $data, Request $request): Testimonial
    {
        unset($data['avatar'], $data['remove_avatar']);

        if ($request->boolean('remove_avatar')) {
            $this->deleteStoredAvatar($testimonial->avatar_url);
            $data['avatar_url'] = null;
        } elseif ($request->hasFile('avatar')) {
            $this->deleteStoredAvatar($testimonial->avatar_url);
            $path = $request->file('avatar')->store('testimonials', 'public');
            $data['avatar_url'] = '/storage/'.$path;
        }

        $data['rating'] = $data['rating'] ?? 5;

        $testimonial->update($data);

        return $testimonial;
    }

    public function destroy(Testimonial $testimonial): void
    {
        $this->deleteStoredAvatar($testimonial->avatar_url);
        $testimonial->delete();
    }

    private function deleteStoredAvatar(?string $avatarUrl): void
    {
        if (! $avatarUrl || ! str_starts_with($avatarUrl, '/storage/')) {
            return;
        }
        $path = str_replace('/storage/', '', $avatarUrl);
        Storage::disk('public')->delete($path);
    }
}
