<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Quick actions shared by the content lists: show or hide an item, and
 * save a new order after drag and drop.
 */
class ListActionController extends Controller
{
    /** URL segment => [model, permission prefix, can be reordered]. */
    private const RESOURCES = [
        'services' => [Service::class, 'services', true],
        'faqs' => [FAQ::class, 'faqs', true],
        'team-members' => [TeamMember::class, 'team_members', true],
        'testimonials' => [Testimonial::class, 'testimonials', false],
    ];

    public function toggle(Request $request, string $resource, int $id): RedirectResponse
    {
        [$model] = $this->resolve($request, $resource);

        /** @var Model $item */
        $item = $model::query()->findOrFail($id);
        $item->update(['is_active' => ! $item->is_active]);

        return back()->with('success', $item->is_active ? 'Now shown on the website.' : 'Now hidden from the website.');
    }

    public function reorder(Request $request, string $resource): RedirectResponse
    {
        [$model, , $sortable] = $this->resolve($request, $resource);
        abort_unless($sortable, 404);

        $validated = $request->validate([
            'ids' => ['required', 'array', 'max:200'],
            'ids.*' => ['integer', 'distinct'],
            'offset' => ['nullable', 'integer', 'min:0'],
        ]);

        $offset = (int) ($validated['offset'] ?? 0);

        DB::transaction(function () use ($model, $validated, $offset) {
            foreach ($validated['ids'] as $index => $id) {
                $model::query()->whereKey($id)->update(['sort_order' => $offset + $index + 1]);
            }
        });

        return back()->with('success', 'Order saved.');
    }

    /**
     * @return array{class-string<Model>, string, bool}
     */
    private function resolve(Request $request, string $resource): array
    {
        abort_unless(isset(self::RESOURCES[$resource]), 404);

        [$model, $permission, $sortable] = self::RESOURCES[$resource];

        $user = $request->user();
        abort_unless($user->isSuperAdmin() || $user->hasPermission("{$permission}.update"), 403);

        return [$model, $permission, $sortable];
    }
}
