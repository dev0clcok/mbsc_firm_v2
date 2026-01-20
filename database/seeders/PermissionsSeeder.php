<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PermissionsSeeder extends Seeder
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
    }
}

