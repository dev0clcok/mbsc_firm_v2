<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create the admin without the factory: Faker is a dev package and is not installed in production.
        if (User::count() === 0) {
            User::query()->create([
                'name' => 'Admin',
                'email' => config('admin.super_admin_email'),
                'password' => config('admin.default_password'),
                'email_verified_at' => now(),
            ]);
        }

        // Seed in order (services first as other seeders may depend on them)
        $this->call([
            PermissionsSeeder::class,
            SiteSettingSeeder::class,
            ServiceSeeder::class,
            TeamMemberSeeder::class,
            TestimonialSeeder::class,
            FAQSeeder::class,
        ]);
    }
}
