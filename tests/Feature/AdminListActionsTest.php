<?php

use App\Models\FAQ;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create(['email' => config('admin.super_admin_email')]);
});

test('an admin can hide and show an item from the list', function () {
    $service = Service::create(['slug' => 'vat', 'title' => 'VAT', 'is_active' => true]);

    $this->actingAs($this->admin)->patch("/admin/services/{$service->id}/toggle")->assertRedirect();
    expect($service->fresh()->is_active)->toBeFalse();
    $this->get('/services/vat')->assertNotFound();

    $this->patch("/admin/services/{$service->id}/toggle");
    expect($service->fresh()->is_active)->toBeTrue();
});

test('dragging saves the new order, continuing from the page offset', function () {
    $a = FAQ::create(['question' => 'A', 'answer' => 'a', 'sort_order' => 1]);
    $b = FAQ::create(['question' => 'B', 'answer' => 'b', 'sort_order' => 2]);
    $c = FAQ::create(['question' => 'C', 'answer' => 'c', 'sort_order' => 3]);

    $this->actingAs($this->admin)
        ->post('/admin/faqs/reorder', ['ids' => [$c->id, $a->id, $b->id], 'offset' => 15])
        ->assertRedirect();

    expect(FAQ::orderBy('sort_order')->pluck('question')->all())->toBe(['C', 'A', 'B'])
        ->and($c->fresh()->sort_order)->toBe(16);
});

test('list actions are limited to known lists and to staff with permission', function () {
    $testimonial = Testimonial::create(['name' => 'Client', 'text' => 'Good.', 'is_active' => true]);

    $this->actingAs($this->admin);
    $this->post('/admin/testimonials/reorder', ['ids' => [$testimonial->id]])->assertNotFound();
    $this->patch('/admin/users/1/toggle')->assertNotFound();

    $this->actingAs(User::factory()->create());
    $this->patch("/admin/testimonials/{$testimonial->id}/toggle")->assertForbidden();
    expect($testimonial->fresh()->is_active)->toBeTrue();
});
