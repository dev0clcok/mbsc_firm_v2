<?php

use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('admin.dashboard'));
    $response->assertRedirect(route('login'));
});

test('users without admin access cannot visit the dashboard', function () {
    $this->actingAs(User::factory()->create());

    $this->get(route('admin.dashboard'))->assertForbidden();
});

test('an admin sees the dashboard with counts and a checklist', function () {
    $user = User::factory()->create(['email' => config('admin.super_admin_email')]);
    $this->actingAs($user);

    $response = $this->get(route('admin.dashboard'));
    $response->assertStatus(200);
});
test('the dashboard counts enquiries in eight weekly buckets', function () {
    $this->actingAs(User::factory()->create(['email' => config('admin.super_admin_email')]));

    $make = fn ($daysAgo) => App\Models\Enquiry::create(['name' => 'A', 'phone' => '017', 'message' => 'Hello there.'])
        ->forceFill(['created_at' => now()->subDays($daysAgo)])->save();

    $make(0);
    $make(1);
    $make(10);
    $make(90);

    $this->get(route('admin.dashboard'))->assertInertia(fn ($page) => $page
        ->has('weeklyEnquiries', 8)
        ->where('weeklyEnquiries.7.count', 2)
        ->where('weeklyEnquiries.6.count', 1)
        ->where('weeklyEnquiries.0.count', 0));
});

test('the dashboard only sends counts for lists the person may open', function () {
    $role = App\Models\Role::create(['name' => 'FAQ editor', 'slug' => 'faq-editor']);
    $role->permissions()->sync(
        collect(['admin.access', 'faqs.list'])->map(fn ($slug) => App\Models\Permission::firstOrCreate(['slug' => $slug], ['name' => $slug])->id)
    );
    $user = User::factory()->create();
    $user->roles()->sync([$role->id]);

    App\Models\Enquiry::create(['name' => 'Private', 'phone' => '01700000002', 'message' => 'Not for this role.']);
    App\Models\FAQ::create(['question' => 'Q', 'answer' => 'A', 'is_active' => true]);

    $this->actingAs($user)->get(route('admin.dashboard'))->assertOk()->assertInertia(fn ($page) => $page
        ->where('stats.faqs', 1)
        ->where('stats.services', null)
        ->where('stats.team_members', null)
        ->where('stats.testimonials', null)
        ->where('stats.new_enquiries', null)
        ->where('stats.enquiries', null)
        ->where('recentEnquiries', null)
        ->where('weeklyEnquiries', null)
        ->where('checklist', []));
});
