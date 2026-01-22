<?php

namespace App\Http\Services;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class UserService
{
    /**
     * @return array<int, array{id:int,name:string,slug:string}>
     */
    public function roleOptions(): array
    {
        return Role::query()
            ->orderBy('name')
            ->get(['id', 'name', 'slug'])
            ->map(fn (Role $r) => ['id' => $r->id, 'name' => $r->name, 'slug' => $r->slug])
            ->all();
    }

    public function index(Request $request): LengthAwarePaginator
    {
        $search = (string) $request->query('search', '');
        $roleId = $request->query('role_id');
        $verified = (string) $request->query('verified', '');

        $dateFormat = (string) config('app.settings.date_format', 'Y-m-d');
        $superAdminEmail = (string) config('admin.super_admin_email');

        return User::query()
            ->when($superAdminEmail === '', fn (Builder $q) => $q->whereKeyNot(1))
            ->when($superAdminEmail !== '', fn (Builder $q) => $q->where('email', '!=', $superAdminEmail))
            ->when($search !== '', function (Builder $q) use ($search) {
                $q->where(function (Builder $q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($roleId !== null && $roleId !== '', function (Builder $q) use ($roleId) {
                $q->whereHas('roles', fn (Builder $rq) => $rq->where('roles.id', (int) $roleId));
            })
            ->when($verified === '1', fn (Builder $q) => $q->whereNotNull('email_verified_at'))
            ->when($verified === '0', fn (Builder $q) => $q->whereNull('email_verified_at'))
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->through(fn (User $u) => [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'email_verified_at' => $u->email_verified_at,
                'created_at' => $u->created_at?->format($dateFormat),
            ])
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
                'email_verified_at' => now()
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

    public function destroy(User $user): void
    {
        try {
            DB::beginTransaction();

            // Safety: detach relations explicitly (pivots also have cascadeOnDelete)
            $user->roles()->detach();
            $user->permissions()->detach();

            $user->delete();

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

