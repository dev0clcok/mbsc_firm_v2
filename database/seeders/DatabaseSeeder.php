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
        // Create a test user if needed (so we can attach roles/permissions in seeders).
        if (User::count() === 0) {
            User::factory()->create([
                'name' => 'Admin',
                'email' => config('admin.super_admin_email'),
            ]);
        }

        // Seed in order (services first as other seeders may depend on them)
        $this->call([
            PermissionsSeeder::class,
            ServiceSeeder::class,
            TestimonialSeeder::class,
            FAQSeeder::class,
        ]);
    }
}
