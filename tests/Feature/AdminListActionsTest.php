<?php

use App\Models\FAQ;
use App\Models\Service;
use App\Models\TeamMember;
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

test('new records are added at the end of their list', function () {
    $founder = TeamMember::create(['name' => 'Founder', 'position' => 'Founder', 'sort_order' => 1]);
    Service::create(['slug' => 'vat', 'title' => 'VAT', 'sort_order' => 4]);
    FAQ::create(['question' => 'First', 'answer' => 'a', 'sort_order' => 2]);
    Testimonial::create(['name' => 'First', 'text' => 'Good.', 'sort_order' => 7]);

    $this->actingAs($this->admin);

    // The create forms send 0 when the order field is left alone.
    $this->post('/admin/team-members', ['name' => 'New colleague', 'position' => 'Associate', 'sort_order' => 0, 'is_active' => true])->assertRedirect();
    $this->post('/admin/services', ['slug' => 'tax', 'title' => 'Tax', 'sort_order' => 0, 'is_active' => true])->assertRedirect();
    $this->post('/admin/faqs', ['question' => 'Second', 'answer' => 'b', 'sort_order' => 0, 'is_active' => true])->assertRedirect();
    $this->post('/admin/testimonials', ['name' => 'Second', 'text' => 'Fine.', 'sort_order' => 0, 'is_active' => true])->assertRedirect();

    expect(TeamMember::orderBy('sort_order')->orderBy('id')->pluck('name')->all())->toBe(['Founder', 'New colleague'])
        ->and(TeamMember::where('name', 'New colleague')->value('sort_order'))->toBe(2)
        ->and($founder->fresh()->sort_order)->toBe(1)
        ->and(Service::where('slug', 'tax')->value('sort_order'))->toBe(5)
        ->and(FAQ::where('question', 'Second')->value('sort_order'))->toBe(3)
        ->and(Testimonial::where('name', 'Second')->value('sort_order'))->toBe(8);
});

test('a position chosen on the create form is kept', function () {
    Service::create(['slug' => 'vat', 'title' => 'VAT', 'sort_order' => 4]);

    $this->actingAs($this->admin)
        ->post('/admin/services', ['slug' => 'tax', 'title' => 'Tax', 'sort_order' => 2, 'is_active' => true])
        ->assertRedirect();

    expect(Service::where('slug', 'tax')->value('sort_order'))->toBe(2);
});
