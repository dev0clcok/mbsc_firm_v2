<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        /** @var array{groups: array<int, array{key:string,label:string,modules: array<int, array{key:string,label:string,permissions: array<int, array{slug:string,label:string}>}>}>} $defs */
        $defs = config('permissions');

        foreach (($defs['groups'] ?? []) as $group) {
            foreach (($group['modules'] ?? []) as $module) {
                foreach (($module['permissions'] ?? []) as $perm) {
                    Permission::firstOrCreate(
                        ['slug' => $perm['slug']],
                        ['name' => $module['label'].' - '.$perm['label']],
                    );
                }
            }
        }

        $adminRole = Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Admin']);
        $superAdminRole = Role::firstOrCreate(['slug' => 'super_admin'], ['name' => 'Super Admin']);

        // Admin gets all permissions (super admin bypasses checks anyway).
        $adminRole->permissions()->syncWithoutDetaching(Permission::query()->pluck('id')->all());

        // Determine Super Admin: configured email, otherwise first user.
        $superEmail = (string) config('admin.super_admin_email');
        $superUser = $superEmail !== ''
            ? User::query()->where('email', $superEmail)->first()
            : User::query()->orderBy('id')->first();

        if ($superUser) {
            $superUser->roles()->syncWithoutDetaching([$superAdminRole->id]);

            if (app()->environment('local') && ! $superUser->email_verified_at) {
                $superUser->forceFill(['email_verified_at' => Carbon::now()])->save();
            }
        }

        // Also assign admin role to the configured ADMIN_EMAIL (if set), otherwise first user.
        $adminEmail = (string) env('ADMIN_EMAIL');
        $adminUser = $adminEmail !== ''
            ? User::query()->where('email', $adminEmail)->first()
            : User::query()->orderBy('id')->first();

        if ($adminUser) {
            $adminUser->roles()->syncWithoutDetaching([$adminRole->id]);
        }
    }
}

