<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class UserService
{
    public function index(Request $request): LengthAwarePaginator
    {
        return User::query()
            ->where('email', '!=', config('admin.super_admin_email'))
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();
    }

    /**
     * @param array{name:string,email:string,password:string,roles?:array<int,int>} $data
     */
    public function store(array $data): User
    {
        try {
            DB::beginTransaction();

            $user = User::query()->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $user->roles()->sync($data['roles'] ?? []);

            DB::commit();

            return $user;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param array{roles?:array<int,int>} $data
     */
    public function update(User $user, array $data): User
    {
        try {
            DB::beginTransaction();

            $user->roles()->sync($data['roles'] ?? []);

            DB::commit();

            return $user;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

