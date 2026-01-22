<?php

namespace App\Http\Services;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class RoleService
{
    public function index(Request $request): LengthAwarePaginator
    {
        $search = (string) $request->query('search', '');
        $hasUsers = (string) $request->query('has_users', '');

        return Role::query()
            ->withCount('users')
            ->when($search !== '', function (Builder $q) use ($search) {
                $q->where(function (Builder $q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($hasUsers === '1', fn (Builder $q) => $q->having('users_count', '>', 0))
            ->when($hasUsers === '0', fn (Builder $q) => $q->having('users_count', '=', 0))
            ->orderBy('id', 'desc')
            ->paginate(config('app.settings.pagination.per_page'))
            ->withQueryString();
    }

    /**
     * @param array{name:string,slug?:string|null,description?:string|null,permissions?:array<int,string>} $data
     */
    public function store(array $data): Role
    {
        try {
            DB::beginTransaction();

            $role = Role::query()->create([
                'name' => $data['name'],
                'slug' => ($data['slug'] ?? null) ?: Str::slug($data['name']),
                'description' => $data['description'] ?? null,
            ]);

            $permIds = Permission::query()
                ->whereIn('slug', $data['permissions'] ?? [])
                ->pluck('id')
                ->all();

            $role->permissions()->sync($permIds);

            DB::commit();

            return $role;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param array{name:string,slug?:string|null,description?:string|null,permissions?:array<int,string>} $data
     */
    public function update(Role $role, array $data): Role
    {
        try {
            DB::beginTransaction();

            $role->update([
                'name' => $data['name'],
                'slug' => ($data['slug'] ?? null) ?: Str::slug($data['name']),
                'description' => $data['description'] ?? null,
            ]);

            $permIds = Permission::query()
                ->whereIn('slug', $data['permissions'] ?? [])
                ->pluck('id')
                ->all();

            $role->permissions()->sync($permIds);

            DB::commit();

            return $role;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
