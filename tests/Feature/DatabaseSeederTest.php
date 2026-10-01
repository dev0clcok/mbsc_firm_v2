<?php

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Hash;

/**
 * Production installs have no dev packages, so seeding must not rely on
 * Faker to create the first admin account.
 */
test('seeding creates the admin account from configuration', function () {
    config(['admin.super_admin_email' => 'owner@example.com', 'admin.default_password' => 'first-password']);

    $this->seed(DatabaseSeeder::class);

    $admin = User::query()->where('email', 'owner@example.com')->sole();

    expect($admin->name)->toBe('Admin')
        ->and($admin->email_verified_at)->not->toBeNull()
        ->and(Hash::check('first-password', $admin->password))->toBeTrue()
        ->and($admin->isSuperAdmin())->toBeTrue();
});

test('seeding does not add an admin when a user already exists', function () {
    $existing = User::factory()->create();

    $this->seed(DatabaseSeeder::class);

    expect(User::query()->count())->toBe(1)
        ->and(User::query()->sole()->is($existing))->toBeTrue();
});

test('the database seeder does not use model factories', function () {
    expect(file_get_contents(database_path('seeders/DatabaseSeeder.php')))->not->toContain('factory(');
});
