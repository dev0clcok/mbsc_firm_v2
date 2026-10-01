<?php

use App\Models\Service;
use App\Models\User;
use App\Support\SvgIcon;

beforeEach(function () {
    $this->admin = User::factory()->create(['email' => config('admin.super_admin_email')]);
});

test('a malicious icon is cleaned when a service is saved', function () {
    $icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" onload="steal()">'
        .'<image href="x" onerror="steal()"/><script>steal()</script>'
        .'<foreignObject><body onload="steal()"/></foreignObject>'
        .'<a href="javascript:steal()"><path d="M1 1"/></a>'
        .'<path d="M4 2v20" onclick="steal()" style="background:url(x)"/></svg>';

    $this->actingAs($this->admin)
        ->post('/admin/services', ['slug' => 'vat', 'title' => 'VAT', 'icon_svg' => $icon, 'is_active' => true])
        ->assertRedirect();

    $saved = Service::where('slug', 'vat')->value('icon_svg');

    expect($saved)->toContain('<svg')->toContain('d="M4 2v20"')
        ->not->toContain('steal')
        ->not->toContain('onerror')
        ->not->toContain('onload')
        ->not->toContain('onclick')
        ->not->toContain('<script')
        ->not->toContain('<image')
        ->not->toContain('foreignObject')
        ->not->toContain('javascript:')
        ->not->toContain('style=');

    $this->get('/services/vat')->assertOk()->assertDontSee('steal()', false);
});

test('an ordinary line icon is kept as it is drawn', function () {
    $icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 8H8"/><circle cx="12" cy="12" r="3"/></svg>';

    $clean = SvgIcon::clean($icon);

    expect($clean)->toContain('viewBox="0 0 24 24"')
        ->toContain('stroke="currentColor"')
        ->toContain('<path d="M14 8H8"')
        ->toContain('<circle cx="12" cy="12" r="3"');
});

test('text that is not an svg is not kept as an icon', function () {
    expect(SvgIcon::clean('<img src=x onerror=steal()>'))->toBeNull()
        ->and(SvgIcon::clean('hello'))->toBeNull()
        ->and(SvgIcon::clean(''))->toBeNull();

    $service = Service::create(['slug' => 'tax', 'title' => 'Tax', 'icon_svg' => '<img src=x onerror=steal()>']);

    expect($service->fresh()->icon_svg)->toBeNull();
});

test('an icon edited on an existing service is cleaned too', function () {
    $service = Service::create(['slug' => 'audit', 'title' => 'Audit', 'is_active' => true]);

    $this->actingAs($this->admin)
        ->put("/admin/services/{$service->id}", [
            'slug' => 'audit', 'title' => 'Audit', 'is_active' => true,
            'icon_svg' => '<svg viewBox="0 0 24 24"><path d="M1 1" onmouseover="steal()"/></svg>',
        ])
        ->assertRedirect();

    expect($service->fresh()->icon_svg)->toContain('d="M1 1"')->not->toContain('onmouseover');
});
