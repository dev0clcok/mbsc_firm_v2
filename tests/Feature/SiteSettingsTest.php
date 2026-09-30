<?php

use App\Models\SiteSetting;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('guests cannot open site settings', function () {
    $this->get('/admin/site-settings')->assertRedirect(route('login'));
});

test('an admin can update site settings', function () {
    $this->actingAs(User::factory()->create(['email' => config('admin.super_admin_email')]));

    $this->put('/admin/site-settings', [
        'phone' => '+88 01700-000000',
        'whatsapp' => '+88 01700-000000',
        'email' => 'office@example.com',
        'office_hours' => 'Saturday to Thursday, 10:00 AM to 7:00 PM',
        'linkedin_url' => 'https://www.linkedin.com/company/example',
        'facebook_url' => '',
    ])->assertRedirect('/admin/site-settings');

    expect(SiteSetting::get('phone'))->toBe('+88 01700-000000')
        ->and(SiteSetting::get('facebook_url'))->toBeNull();
});

test('invalid settings are rejected', function () {
    $this->actingAs(User::factory()->create(['email' => config('admin.super_admin_email')]));

    $this->put('/admin/site-settings', ['email' => 'not-an-email', 'linkedin_url' => 'javascript:alert(1)'])
        ->assertSessionHasErrors(['email', 'linkedin_url']);
});

test('public pages receive derived contact links and only filled-in socials', function () {
    SiteSetting::put([
        'phone' => '+88 01700-000000',
        'whatsapp' => '+88 01700-111111',
        'facebook_url' => 'https://facebook.com/example',
        'linkedin_url' => '',
    ]);

    $this->get('/contact')->assertInertia(fn (Assert $page) => $page
        ->where('site.phone_href', 'tel:+8801700000000')
        ->where('site.whatsapp_url', 'https://wa.me/8801700111111')
        ->where('site.socials', [['platform' => 'facebook', 'url' => 'https://facebook.com/example']])
    );
});

test('office hours keep the time and AM/PM together', function () {
    SiteSetting::put(['office_hours' => 'Saturday to Thursday, 10:00 AM to 7:00 PM']);

    expect(SiteSetting::forPublic()['office_hours'])->toBe("Saturday to Thursday, 10:00\u{00A0}AM to 7:00\u{00A0}PM");
});
