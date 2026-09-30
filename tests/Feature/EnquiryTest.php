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

    $this->post('/enquiries', $valid)->assertSessionHasErrors('form');

    expect(Enquiry::count())->toBe(5);
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

test('an admin can open an enquiry, assign it and add notes', function () use ($valid) {
    $enquiry = Enquiry::create([...$valid, 'phone' => '01700-000000']);
    $admin = User::factory()->create(['email' => config('admin.super_admin_email')]);
    $this->actingAs($admin);

    $this->get("/admin/enquiries/{$enquiry->id}")->assertOk()->assertInertia(fn ($page) => $page
        ->component('admin/Enquiries/Show')
        ->where('enquiry.whatsapp_number', '8801700000000')
        ->has('enquiry.notes', 0));

    $this->put("/admin/enquiries/{$enquiry->id}", ['assigned_to' => $admin->id])->assertRedirect();
    expect($enquiry->fresh()->assigned_to)->toBe($admin->id)
        ->and($enquiry->fresh()->status)->toBe('new');

    $this->post("/admin/enquiries/{$enquiry->id}/notes", ['body' => ''])->assertSessionHasErrors('body');
    $this->post("/admin/enquiries/{$enquiry->id}/notes", ['body' => 'Called, no answer.'])->assertRedirect();

    $note = $enquiry->notes()->sole();
    expect($note->body)->toBe('Called, no answer.')->and($note->user_id)->toBe($admin->id);
});

test('the enquiry list filters by status, search and date and counts each status', function () use ($valid) {
    Enquiry::create($valid);
    Enquiry::create([...$valid, 'name' => 'Karim', 'status' => 'closed']);
    $old = Enquiry::create([...$valid, 'name' => 'Old one']);
    $old->forceFill(['created_at' => now()->subDays(40)])->save();

    $this->actingAs(User::factory()->create(['email' => config('admin.super_admin_email')]));

    $this->get('/admin/enquiries?status=closed')->assertInertia(fn ($page) => $page
        ->has('enquiries.data', 1)
        ->where('enquiries.data.0.name', 'Karim')
        ->where('counts', ['all' => 3, 'new' => 2, 'contacted' => 0, 'closed' => 1]));

    $this->get('/admin/enquiries?search=Karim')->assertInertia(fn ($page) => $page->has('enquiries.data', 1)->where('counts.all', 1));
    $this->get('/admin/enquiries?from='.now()->subDays(7)->toDateString())->assertInertia(fn ($page) => $page->has('enquiries.data', 2));
    $this->get('/admin/enquiries?sort=oldest')->assertInertia(fn ($page) => $page->where('enquiries.data.0.name', 'Old one'));
    $this->get('/admin/enquiries?status=archived')->assertSessionHasErrors('status');
});

test('enquiries export as CSV with formula characters neutralised', function () use ($valid) {
    Enquiry::create([...$valid, 'name' => '=HYPERLINK("http://evil")', 'message' => 'Hello there, I need VAT help.']);
    Enquiry::create([...$valid, 'name' => 'Closed one', 'status' => 'closed']);

    $this->actingAs(User::factory()->create(['email' => config('admin.super_admin_email')]));

    $response = $this->get('/admin/enquiries/export?status=new')->assertOk();
    expect($response->headers->get('content-type'))->toContain('text/csv');

    $csv = $response->streamedContent();
    expect($csv)->toContain('Received,Name,Phone')
        ->toContain("'=HYPERLINK")
        ->not->toContain('Closed one');
});

test('staff without the enquiries permission cannot export', function () {
    $this->actingAs(User::factory()->create());

    $this->get('/admin/enquiries/export')->assertForbidden();
});
