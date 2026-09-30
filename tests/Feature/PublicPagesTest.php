<?php

use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    Service::create(['slug' => 'vat', 'title' => 'VAT Advisory & Compliance', 'short_description' => 'BIN registration and returns.', 'is_active' => true, 'sort_order' => 1]);
    Service::create(['slug' => 'hidden', 'title' => 'Hidden service', 'is_active' => false, 'sort_order' => 2]);
});

test('public pages render with a description and canonical URL', function (string $path) {
    $this->get($path)
        ->assertOk()
        ->assertSee('<meta name="description"', false)
        ->assertSee('<link rel="canonical"', false)
        ->assertSee('application/ld+json', false);
})->with(['/', '/services', '/services/vat', '/about', '/contact']);

test('each active service has its own page and inactive ones are not found', function () {
    $this->get('/services/vat')->assertInertia(fn (Assert $page) => $page
        ->component('Service')
        ->where('service.title', 'VAT Advisory & Compliance'));

    $this->get('/services/hidden')->assertNotFound();
    $this->get('/services/missing')->assertNotFound();
});

test('the home page only lists active testimonials', function () {
    Testimonial::create(['name' => 'Hidden', 'text' => 'Not shown', 'is_active' => false]);

    $this->get('/')->assertInertia(fn (Assert $page) => $page->has('testimonials', 0));
});

test('the sitemap lists public pages and active services', function () {
    $this->get('/sitemap.xml')
        ->assertOk()
        ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
        ->assertSee(route('services.show', 'vat'))
        ->assertDontSee(route('services.show', 'hidden'));
});

test('robots.txt points to the sitemap and keeps the admin out', function () {
    $this->get('/robots.txt')
        ->assertOk()
        ->assertSee('Disallow: /admin')
        ->assertSee('Sitemap: '.route('sitemap'));
});

test('admin pages are marked noindex', function () {
    $this->get('/login')->assertSee('<meta name="robots" content="noindex">', false);
});

test('structured data only contains settings that are filled in', function () {
    SiteSetting::put(['phone' => '+88 01700-000000', 'email' => '', 'facebook_url' => '']);

    $data = SiteSetting::structuredData();

    expect($data['telephone'])->toBe('+8801700000000')
        ->and($data)->not->toHaveKeys(['email', 'sameAs']);
});
