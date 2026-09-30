<?php

use App\Mail\EnquiryReceived;
use App\Models\Enquiry;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    Mail::fake();
    SiteSetting::put(['enquiry_email' => 'office@example.com']);
});

$valid = [
    'name' => 'Rahim Uddin',
    'phone' => '+88 01700-000000',
    'email' => '',
    'service' => 'VAT Advisory & Compliance',
    'message' => 'I need help with BIN registration for my shop.',
];

test('a visitor can send an enquiry, which is stored and emailed', function () use ($valid) {
    $this->from('/contact')->post('/enquiries', $valid)
        ->assertRedirect('/contact')
        ->assertSessionHas('success');

    $enquiry = Enquiry::sole();
    expect($enquiry->status)->toBe('new')->and($enquiry->name)->toBe('Rahim Uddin');

    Mail::assertQueued(EnquiryReceived::class, fn ($mail) => $mail->hasTo('office@example.com'));
});

test('an enquiry needs a name, a message and a way to reply', function () {
    $this->post('/enquiries', ['name' => '', 'message' => 'short'])
        ->assertSessionHasErrors(['name', 'message', 'phone', 'email']);

    expect(Enquiry::count())->toBe(0);
    Mail::assertNothingQueued();
});

test('honeypot submissions look successful but are discarded', function () use ($valid) {
    $this->post('/enquiries', [...$valid, 'website' => 'https://spam.example'])
        ->assertSessionHas('success');

    expect(Enquiry::count())->toBe(0);
    Mail::assertNothingQueued();
});

test('enquiries are rate limited per visitor', function () use ($valid) {
    foreach (range(1, 5) as $i) {
        $this->post('/enquiries', $valid)->assertSessionHas('success');
    }

    $this->post('/enquiries', $valid)->assertStatus(429);
});

test('an admin can list enquiries and change their status', function () use ($valid) {
    $enquiry = Enquiry::create($valid);
    $this->actingAs(User::factory()->create(['email' => config('admin.super_admin_email')]));

    $this->get('/admin/enquiries')->assertOk();

    $this->put("/admin/enquiries/{$enquiry->id}", ['status' => 'contacted'])->assertRedirect();
    expect($enquiry->fresh()->status)->toBe('contacted');

    $this->put("/admin/enquiries/{$enquiry->id}", ['status' => 'archived'])->assertSessionHasErrors('status');
});

test('guests cannot see enquiries', function () {
    $this->get('/admin/enquiries')->assertRedirect(route('login'));
});
