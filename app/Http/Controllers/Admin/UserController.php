<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Services\UserService;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:users.list', only: ['index']),
            new Middleware('permission:users.create', only: ['create', 'store']),
            new Middleware('permission:users.update', only: ['edit', 'update']),
        ];
    }

    public function index(Request $request, UserService $userService): Response
    {
        $users = $userService->index($request);

        return Inertia::render('admin/Users/Index', [
            'users' => $users,
            'filters' => [
                'search' => (string) $request->query('search', ''),
                'role_id' => $request->query('role_id'),
                'verified' => (string) $request->query('verified', ''),
            ],
            'roleOptions' => $userService->roleOptions(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/Users/Create', [
            'roles' => Role::query()->orderBy('name')->get(['id', 'name', 'slug']),
        ]);
    }

    public function store(StoreUserRequest $request, UserService $userService): RedirectResponse
    {
        try {
            $userService->store($request->validated());

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'User created successfully.');
        } catch (Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with('error', 'Failed to create user. Please try again.');
        }
    }

    public function edit(User $user): Response
    {
        $user->load('roles');

        return Inertia::render('admin/Users/Edit', [
            'user' => $user,
            'roles' => Role::query()->orderBy('name')->get(),
            'selectedRoles' => $user->roles->pluck('id')->values(),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user, UserService $userService): RedirectResponse
    {
        try {
            $userService->update($user, $request->validated());

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'User roles updated successfully.');
        } catch (Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with('error', 'Failed to update user. Please try again.');
        }
    }
}
