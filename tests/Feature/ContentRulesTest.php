<?php

use App\Models\FAQ;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\User;
use Database\Seeders\FAQSeeder;
use Database\Seeders\ServiceSeeder;
use Illuminate\Support\Facades\DB;

/**
 * The firm does not publish turnaround or response-time promises, or
 * claims about services it does not list.
 */
$forbidden = '/\b\d+\s*(?:-|to)\s*\d+\s+(?:working\s+)?days\b|\bworking days?\b|\bon time\b|\bexpedited\b|\bguarantee|\bBIDA\b|\bliaison office\b/i';

test('seeded content makes no timing promises', function () use ($forbidden) {
    $this->seed([ServiceSeeder::class, FAQSeeder::class]);

    $text = FAQ::all()->map(fn (FAQ $f) => $f->question.' '.$f->answer)
        ->concat(Service::all()->map(fn (Service $s) => json_encode([$s->short_description, $s->description, $s->features, $s->process_steps, $s->timeline])))
        ->implode("\n");

    expect($text)->not->toMatch($forbidden);

    foreach (['/', '/faqs', '/contact', '/services/vat'] as $url) {
        expect($this->get($url)->assertOk()->getContent())->not->toMatch($forbidden);
    }
});

test('seeded wording already stored is corrected, and edited text is left alone', function () {
    $vat = FAQ::create(['question' => 'How long does VAT registration take?', 'answer' => 'VAT registration usually takes 3-7 working days after submission of all required documents. We ensure expedited processing for urgent requirements.']);
    $foreign = FAQ::create(['question' => 'Do you provide services for foreign companies?', 'answer' => 'Yes, we specialize in assisting foreign companies with BIDA registration, branch office setup, liaison office establishment, and all regulatory compliance requirements.']);
    $edited = FAQ::create(['question' => 'Edited by staff', 'answer' => 'Our own wording.']);
    $service = Service::create(['slug' => 'vat', 'title' => 'VAT', 'process_steps' => [
        ['title' => 'Delivery', 'description' => 'You receive the result on time, with ongoing support.'],
        ['title' => 'Custom', 'description' => 'Written by staff.'],
    ]]);
    DB::table('site_settings')->insert(['key' => 'response_time', 'value' => 'within one working day']);

    (require database_path('migrations/2026_10_01_000003_remove_timing_promises_from_content.php'))->up();

    expect($vat->fresh()->answer)->not->toContain('working days')->not->toContain('expedited')
        ->and($foreign->fresh()->answer)->not->toContain('BIDA')
        ->and($edited->fresh()->answer)->toBe('Our own wording.')
        ->and($service->fresh()->process_steps)->toBe([
            ['title' => 'Delivery', 'description' => 'You receive the completed documents, with ongoing support.'],
            ['title' => 'Custom', 'description' => 'Written by staff.'],
        ])
        ->and(DB::table('site_settings')->where('key', 'response_time')->exists())->toBeFalse();
});

test('a response time can no longer be saved or shown', function () {
    $this->actingAs(User::factory()->create(['email' => config('admin.super_admin_email')]));

    expect(SiteSetting::KEYS)->not->toContain('response_time')
        ->and(SiteSetting::forPublic())->not->toHaveKey('response_time');

    $this->get('/admin')->assertInertia(fn ($page) => $page
        ->where('checklist', fn ($items) => collect($items)->pluck('key')->doesntContain('response_time')));
});
