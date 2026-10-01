<?php

use App\Mail\EnquiryReceived;
use App\Models\Enquiry;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    Mail::fake();
    SiteSetting::put(['enquiry_email' => 'office@example.com']);
    Service::create(['slug' => 'vat', 'title' => 'VAT Advisory & Compliance', 'is_active' => true]);
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

test('an enquiry is kept when the notification cannot be queued', function () use ($valid) {
    Mail::shouldReceive('to')->once()->andThrow(new RuntimeException('Queue is down.'));

    $this->from('/contact')->post('/enquiries', $valid)
        ->assertRedirect('/contact')
        ->assertSessionHas('success');

    expect(Enquiry::count())->toBe(1);
});

test('the notification is queued, not sent while the visitor waits', function () {
    expect(new EnquiryReceived(new Enquiry))->toBeInstanceOf(Illuminate\Contracts\Queue\ShouldQueue::class);
});

test('an enquiry needs a name, a message and a way to reply', function () {
    $this->post('/enquiries', ['name' => '', 'message' => 'short'])
        ->assertSessionHasErrors(['name', 'message', 'phone', 'email']);

    expect(Enquiry::count())->toBe(0);
    Mail::assertNothingQueued();
});

test('the service must be one that is shown on the site, or left empty', function () use ($valid) {
    Service::create(['slug' => 'hidden', 'title' => 'Hidden service', 'is_active' => false]);

    $this->post('/enquiries', [...$valid, 'service' => 'Anything typed by hand'])->assertSessionHasErrors('service');
    $this->post('/enquiries', [...$valid, 'service' => 'Hidden service'])->assertSessionHasErrors('service');
    expect(Enquiry::count())->toBe(0);

    $this->post('/enquiries', [...$valid, 'service' => ''])->assertSessionHasNoErrors();
    $this->post('/enquiries', $valid)->assertSessionHasNoErrors();
    expect(Enquiry::count())->toBe(2);
});

test('phone numbers typed with Bengali digits are accepted and stored as 0-9', function () use ($valid) {
    $this->post('/enquiries', [...$valid, 'phone' => '০১৭০০-০০০০০২'])->assertSessionHasNoErrors();

    $enquiry = Enquiry::sole();
    expect($enquiry->phone)->toBe('01700-000002')
        ->and($enquiry->phone_normalized)->toBe('01700000002')
        ->and($enquiry->whatsappNumber())->toBe('8801700000002');
});

test('phone numbers must be complete', function (string $phone, bool $ok) use ($valid) {
    $response = $this->post('/enquiries', [...$valid, 'phone' => $phone]);

    $ok ? $response->assertSessionHasNoErrors() : $response->assertSessionHasErrors('phone');
})->with([
    'too short' => ['018681967', false],
    'letters' => ['call me', false],
    'not a mobile prefix' => ['01200000000', false],
    'too long' => ['017000000021', false],
    'foreign without country code' => ['2025550123', false],
    'local mobile' => ['01700000002', true],
    'local with hyphen' => ['01700-000002', true],
    'with +880' => ['+880 1700-000002', true],
    'with 880' => ['8801700000002', true],
    'international' => ['+44 20 7946 0958', true],
]);

test('an enquiry is found by its phone number however it is typed', function () use ($valid) {
    Enquiry::create([...$valid, 'phone' => '+880 1700-000002']);
    Enquiry::create([...$valid, 'name' => 'Someone else', 'phone' => '01811-111111']);

    $this->actingAs(User::factory()->create(['email' => config('admin.super_admin_email')]));

    foreach (['01700000002', '8801700000002', '01700-000002', '+880 1700 000002', '০১৭০০০০০০০২', '1700'] as $search) {
        $this->get('/admin/enquiries?search='.urlencode($search))->assertInertia(fn ($page) => $page
            ->has('enquiries.data', 1)
            ->where('enquiries.data.0.name', 'Rahim Uddin'));
    }
});

test('existing enquiries get a searchable phone number', function () use ($valid) {
    $enquiry = Enquiry::create([...$valid, 'phone' => '8801700000002']);

    $migration = require database_path('migrations/2026_10_01_000002_add_phone_normalized_to_enquiries.php');
    $migration->down();
    expect(Schema::hasColumn('enquiries', 'phone_normalized'))->toBeFalse();
    $migration->up();

    expect($enquiry->fresh()->phone_normalized)->toBe('01700000002');
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

    expect(Enquiry::count())->toBe(5)
        ->and(session('errors')->first('form'))->toContain('Try again in 10 minutes');
});

test('mistakes in the form and honeypot hits do not use up the limit', function () use ($valid) {
    foreach (range(1, 8) as $i) {
        $this->post('/enquiries', ['name' => '', 'message' => 'short'])->assertSessionHasErrors('name');
        $this->post('/enquiries', [...$valid, 'website' => 'https://spam.example'])->assertSessionHas('success');
    }

    foreach (range(1, 5) as $i) {
        $this->post('/enquiries', $valid)->assertSessionHasNoErrors();
    }

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
