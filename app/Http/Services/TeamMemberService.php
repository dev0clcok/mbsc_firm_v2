<?php

namespace App\Http\Services;

use App\Models\TeamMember;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class TeamMemberService
{
    /**
     * @return LengthAwarePaginator|Collection<int, TeamMember>
     */
    public function index(Request $request, bool $pagination = true, ?int $limit = null): LengthAwarePaginator|Collection
    {
        $query = TeamMember::query();

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('position', 'like', "%{$search}%")
                    ->orWhere('specialization', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->integer('status'));
        }

        $query = $query->orderBy('sort_order')->orderBy('id');

        if ($pagination) {
            return $query->paginate(config('app.settings.pagination.per_page'))->withQueryString();
        }

        if ($limit !== null) {
            $query->limit($limit);
        }

        return $query->with('socialLinks')->get();
    }

    /**
     * @param array<string, mixed> $data
     */
    public function store(array $data, Request $request): TeamMember
    {
        $socialLinks = $data['social_links'] ?? [];
        unset($data['image'], $data['social_links']);

        $data['image_url'] = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('team-members', 'public');
            $data['image_url'] = '/storage/'.$path;
        }

        $teamMember = TeamMember::query()->create($data);
        $this->syncSocialLinks($teamMember, $socialLinks);

        return $teamMember;
    }

    /**
     * @param array<string, mixed> $data
     */
    public function update(TeamMember $teamMember, array $data, Request $request): TeamMember
    {
        $socialLinks = $data['social_links'] ?? [];
        unset($data['image'], $data['remove_image'], $data['social_links']);

        if ($request->boolean('remove_image')) {
            $this->deleteStoredImage($teamMember->image_url);
            $data['image_url'] = null;
        } elseif ($request->hasFile('image')) {
            $this->deleteStoredImage($teamMember->image_url);
            $path = $request->file('image')->store('team-members', 'public');
            $data['image_url'] = '/storage/'.$path;
        }

        $teamMember->update($data);
        $this->syncSocialLinks($teamMember, $socialLinks);

        return $teamMember;
    }

    /**
     * @param array<int, array{platform: string, url: string}> $socialLinks
     */
    private function syncSocialLinks(TeamMember $teamMember, array $socialLinks): void
    {
        $teamMember->socialLinks()->delete();

        $valid = array_values(array_filter($socialLinks, function ($s) {
            $platform = $s['platform'] ?? '';
            $url = trim((string) ($s['url'] ?? ''));

            return $platform !== '' && $url !== '';
        }));

        foreach ($valid as $i => $link) {
            $teamMember->socialLinks()->create([
                'platform' => $link['platform'],
                'url' => trim($link['url']),
                'sort_order' => $i,
            ]);
        }
    }

    public function destroy(TeamMember $teamMember): void
    {
        $this->deleteStoredImage($teamMember->image_url);
        $teamMember->delete();
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
