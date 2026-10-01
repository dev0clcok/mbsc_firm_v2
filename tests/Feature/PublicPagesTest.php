<?php

use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use App\Models\User;
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

test('unknown addresses show the site error page with a 404 status', function () {
    $this->get('/no-such-page')
        ->assertNotFound()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Error')
            ->where('status', 404)
            ->has('site.services'));
});

test('missing admin pages keep the default response', function () {
    $this->get('/admin/no-such-page')->assertNotFound()->assertDontSee('data-page');
});

test('a service page carries its steps, documents, timeline and fees', function () {
    Service::where('slug', 'vat')->update([
        'process_steps' => [['title' => 'Consultation', 'description' => 'We talk.']],
        'documents' => ['Trade licence'],
        'timeline' => 'About a week.',
        'fees' => null,
    ]);

    $this->get('/services/vat')->assertInertia(fn (Assert $page) => $page
        ->where('service.process_steps.0.title', 'Consultation')
        ->where('service.documents', ['Trade licence'])
        ->where('service.timeline', 'About a week.')
        ->where('service.fees', null));
});

test('the FAQ page groups questions by service and publishes them as structured data', function () {
    $vat = Service::where('slug', 'vat')->first();
    App\Models\FAQ::create(['question' => 'What are your fees?', 'answer' => 'Ask us.', 'is_active' => true]);
    App\Models\FAQ::create(['service_id' => $vat->id, 'question' => 'How long does BIN take?', 'answer' => '<p>About <strong>a week</strong>.</p><script>alert(1)</script>', 'is_active' => true]);
    App\Models\FAQ::create(['question' => 'Hidden?', 'answer' => 'No.', 'is_active' => false]);

    $this->get('/faqs')
        ->assertOk()
        ->assertSee('"@type":"FAQPage"', false)
        ->assertDontSee('Hidden?')
        ->assertInertia(fn (Assert $page) => $page
            ->component('Faqs')
            ->has('groups', 2)
            ->where('groups.0.title', 'General')
            ->where('groups.1.slug', 'vat')
            ->where('groups.1.faqs.0.answer', '<p>About <strong>a week</strong>.</p>alert(1)'));

    $this->get('/services/vat')->assertInertia(fn (Assert $page) => $page->has('faqs', 1));
    $this->get('/')->assertInertia(fn (Assert $page) => $page->has('faqs', 1));
});

test('the privacy policy is hidden until it is marked as reviewed', function () {
    SiteSetting::put(['privacy_policy' => "## Who we are\n\nA firm.", 'privacy_published' => '0']);

    $this->get('/privacy')->assertNotFound();
    $this->get('/contact')->assertInertia(fn (Assert $page) => $page->where('site.privacy_published', false));
    $this->get('/sitemap.xml')->assertDontSee('/privacy');

    $this->actingAs(App\Models\User::factory()->create(['email' => config('admin.super_admin_email')]));
    $this->put('/admin/privacy-policy', ['policy' => "## Who we are\n\nA firm.", 'published' => true])->assertRedirect();

    $this->get('/privacy')->assertOk()->assertInertia(fn (Assert $page) => $page->component('Privacy')->where('policy', "## Who we are\n\nA firm."));
    $this->get('/contact')->assertInertia(fn (Assert $page) => $page->where('site.privacy_published', true));
    $this->get('/sitemap.xml')->assertSee('/privacy');
});

test('an empty policy cannot be published', function () {
    $this->actingAs(App\Models\User::factory()->create(['email' => config('admin.super_admin_email')]));

    $this->put('/admin/privacy-policy', ['policy' => '', 'published' => true])->assertSessionHasErrors('policy');
});

test('a server error shows the site error page when debug mode is off', function () {
    config(['app.debug' => false]);
    Illuminate\Support\Facades\Route::get('/__crash', fn () => throw new RuntimeException('internal detail'));

    $this->get('/__crash')
        ->assertStatus(500)
        ->assertDontSee('internal detail')
        ->assertInertia(fn (Assert $page) => $page->component('Error')->where('status', 500));
});

test('staff without permission get the site error page with a way back', function () {
    $this->actingAs(User::factory()->create());

    $this->get('/admin/services')
        ->assertForbidden()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Error')
            ->where('status', 403)
            ->where('backTo.href', '/admin'));
});

test('too many attempts in the staff area show the site error page, not a bare 429', function () {
    $this->actingAs(User::factory()->create());

    foreach (range(1, 6) as $i) {
        $this->put('/settings/password', []);
    }

    $this->put('/settings/password', [])
        ->assertTooManyRequests()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Error')
            ->where('status', 429)
            ->where('backTo.label', 'Back to the dashboard'));
});

test('a public error page offers the public links only', function () {
    $this->get('/no-such-page')
        ->assertNotFound()
        ->assertInertia(fn (Assert $page) => $page->component('Error')->where('backTo', null));
});
