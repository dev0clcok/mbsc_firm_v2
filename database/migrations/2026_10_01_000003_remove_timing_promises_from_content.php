<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * The firm's content rules forbid turnaround and response-time
     * promises and claims about services it does not list. This corrects
     * the seeded text where it is still in place; text that staff have
     * since rewritten is left alone.
     */
    public function up(): void
    {
        DB::table('faqs')->where('answer', 'like', '%3-7 working days%')->update([
            'answer' => "The time depends on how complete your documents are and on the VAT office's own processing, so we do not quote a fixed number of days. At the first consultation we go through the documents you have and tell you what is still needed before the application can be submitted.",
        ]);

        DB::table('faqs')->where('answer', 'like', '%BIDA registration, branch office setup%')->update([
            'answer' => 'Foreign-owned companies and their local subsidiaries can use the same services we offer every client: RJSC company registration and filings, income tax, VAT and audit support. Tell us about your situation in an enquiry and we will say whether it is work we take on.',
        ]);

        DB::table('services')->whereNotNull('process_steps')->orderBy('id')->each(function (object $service) {
            $steps = json_decode($service->process_steps, true);

            if (! is_array($steps)) {
                return;
            }

            $updated = array_map(fn ($step) => ($step['description'] ?? null) === 'You receive the result on time, with ongoing support.'
                ? [...$step, 'description' => 'You receive the completed documents, with ongoing support.']
                : $step, $steps);

            if ($updated !== $steps) {
                DB::table('services')->where('id', $service->id)->update(['process_steps' => json_encode($updated)]);
            }
        });

        DB::table('site_settings')->where('key', 'response_time')->delete();
    }

    public function down(): void
    {
        // The removed wording is not restored.
    }
};
