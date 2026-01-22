<?php

namespace App\Http\Services;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class RoleService
{
    public function index(Request $request): LengthAwarePaginator
    {
        return Role::query()
            ->withCount('users')
            ->orderBy('id', 'desc')
            ->paginate(15)
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
