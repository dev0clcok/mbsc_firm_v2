<?php

use App\Http\Services\ImageStore;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create(['email' => config('admin.super_admin_email')]));
});

test('an upload is stored as three WebP sizes and never enlarged', function () {
    $store = app(ImageStore::class);

    $large = $store->store(UploadedFile::fake()->image('wide.jpg', 2400, 1600), 'heroes');
    expect($large)->toMatchArray(['width' => 1280, 'height' => 853])
        ->and($large['url'])->toEndWith('-1280.webp');

    $path = str_replace('/storage/', '', $large['url']);
    Storage::disk('public')->assertExists([$path, ImageStore::variant($path, 800), ImageStore::variant($path, 480)]);
    expect(getimagesize(Storage::disk('public')->path(ImageStore::variant($path, 480)))[0])->toBe(480);

    $small = $store->store(UploadedFile::fake()->image('small.png', 300, 200), 'heroes');
    expect($small)->toMatchArray(['width' => 300, 'height' => 200]);

    $store->delete($large['url']);
    Storage::disk('public')->assertMissing([$path, ImageStore::variant($path, 800), ImageStore::variant($path, 480)]);
});

test('an admin can upload and remove a page hero image', function () {
    $this->post('/admin/site-settings', ['_method' => 'put', 'hero_home' => UploadedFile::fake()->image('hero.jpg', 1600, 900)])
        ->assertSessionHasErrors('hero_home_alt');

    $this->post('/admin/site-settings', ['_method' => 'put', 'hero_home' => UploadedFile::fake()->image('hero.jpg', 1600, 900), 'hero_home_alt' => 'A desk'])
        ->assertRedirect('/admin/site-settings');

    $image = SiteSetting::image('hero_home');
    expect($image)->toMatchArray(['width' => 1280, 'height' => 720, 'alt' => 'A desk']);

    $this->post('/admin/site-settings', ['_method' => 'put', 'hero_home_alt' => 'A tidy desk']);
    expect(SiteSetting::image('hero_home'))->toMatchArray(['url' => $image['url'], 'alt' => 'A tidy desk']);
    Storage::disk('public')->assertExists(str_replace('/storage/', '', $image['url']));

    $this->get('/')->assertSee('rel="preload" as="image"', false);

    $this->post('/admin/site-settings', ['_method' => 'put', 'remove_hero_home' => '1']);

    expect(SiteSetting::image('hero_home'))->toBeNull();
    Storage::disk('public')->assertMissing(str_replace('/storage/', '', $image['url']));
});

test('hero uploads must be images', function () {
    $this->post('/admin/site-settings', ['_method' => 'put', 'hero_about' => UploadedFile::fake()->create('notes.pdf', 100)])
        ->assertSessionHasErrors('hero_about');
});

test('a service image upload is resized and its size recorded', function () {
    $service = Service::create(['slug' => 'vat', 'title' => 'VAT', 'is_active' => true, 'sort_order' => 1]);

    $this->post("/admin/services/{$service->id}", [
        '_method' => 'put',
        'slug' => 'vat',
        'title' => 'VAT',
        'is_active' => '1',
        'image' => UploadedFile::fake()->image('vat.png', 2000, 1000),
        'image_alt' => 'A calculator on a desk',
    ])->assertSessionHasNoErrors();

    expect($service->fresh()->image())->toMatchArray(['width' => 1280, 'height' => 640, 'alt' => 'A calculator on a desk']);
});

test('saving a service keeps filled-in detail rows and drops blank ones', function () {
    $service = Service::create(['slug' => 'vat', 'title' => 'VAT', 'is_active' => true, 'sort_order' => 1]);

    $this->put("/admin/services/{$service->id}", [
        'slug' => 'vat',
        'title' => 'VAT',
        'is_active' => true,
        'features' => ['BIN registration', '  '],
        'documents' => ['', 'Trade licence'],
        'process_steps' => [['title' => 'Consultation', 'description' => 'We talk.'], ['title' => '', 'description' => 'orphan']],
        'timeline' => 'About a week.',
    ])->assertSessionHasNoErrors();

    $service->refresh();
    expect($service->features)->toBe(['BIN registration'])
        ->and($service->documents)->toBe(['Trade licence'])
        ->and($service->process_steps)->toBe([['title' => 'Consultation', 'description' => 'We talk.']])
        ->and($service->timeline)->toBe('About a week.');
});

test('a new service image needs a description', function () {
    $service = Service::create(['slug' => 'vat', 'title' => 'VAT', 'is_active' => true, 'sort_order' => 1]);

    $this->post("/admin/services/{$service->id}", [
        '_method' => 'put', 'slug' => 'vat', 'title' => 'VAT', 'is_active' => '1',
        'image' => UploadedFile::fake()->image('vat.png', 900, 600),
    ])->assertSessionHasErrors('image_alt');
});
