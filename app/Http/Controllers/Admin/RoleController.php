<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;
use App\Http\Services\RoleService;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:roles.list', only: ['index']),
            new Middleware('permission:roles.create', only: ['create', 'store']),
            new Middleware('permission:roles.update', only: ['edit', 'update']),
            new Middleware('permission:roles.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request, RoleService $roleService): Response
    {
        $roles = $roleService->index($request);

        return Inertia::render('admin/Roles/Index', [
            'roles' => $roles,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/Roles/Create', [
            'permissionGroups' => config('permissions.groups'),
        ]);
    }

    public function store(StoreRoleRequest $request, RoleService $roleService): RedirectResponse
    {
        try {
            $roleService->store($request->validated());

            return redirect()
                ->route('admin.roles.index')
                ->with('success', 'Role created successfully.');
        } catch (Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with('error', 'Failed to create role. Please try again.');
        }
    }

    public function edit(Role $role): Response
    {
        $role->load('permissions');

        return Inertia::render('admin/Roles/Edit', [
            'role' => $role,
            'selectedPermissions' => $role->permissions->pluck('slug')->values(),
            'permissionGroups' => config('permissions.groups'),
        ]);
    }

    public function update(UpdateRoleRequest $request, Role $role, RoleService $roleService): RedirectResponse
    {
        try {
            $roleService->update($role, $request->validated());

            return redirect()
                ->route('admin.roles.index')
                ->with('success', 'Role updated successfully.');
        } catch (Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with('error', 'Failed to update role. Please try again.');
        }
    }

    public function destroy(Role $role): RedirectResponse
    {
        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }
}

