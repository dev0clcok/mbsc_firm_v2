<?php

namespace Database\Seeders;

use App\Models\FAQ;
use Illuminate\Database\Seeder;

class FAQSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'What documents are required for company registration?',
                'answer' => 'For company registration, you typically need NID copies of directors, passport-size photos, proposed company names, memorandum of association, and proof of registered office address.',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'question' => 'How long does VAT registration take?',
                'answer' => 'The time depends on how complete your documents are and on the VAT office\'s own processing, so we do not quote a fixed number of days. At the first consultation we go through the documents you have and tell you what is still needed before the application can be submitted.',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'question' => 'Do you provide services for foreign companies?',
                'answer' => 'Foreign-owned companies and their local subsidiaries can use the same services we offer every client: RJSC company registration and filings, income tax, VAT and audit support. Tell us about your situation in an enquiry and we will say whether it is work we take on.',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'question' => 'What are your consultation fees?',
                'answer' => 'We offer free initial consultations. Our service fees vary based on the complexity and scope of work. Contact us for a detailed quotation tailored to your needs.',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'question' => 'Do you provide ongoing compliance support?',
                'answer' => 'Yes, we offer comprehensive ongoing support including VAT return filing, tax compliance, annual filings, and regulatory updates to keep your business compliant.',
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($faqs as $faq) {
            FAQ::updateOrCreate(
                ['question' => $faq['question']],
                $faq
            );
        }
    }
}
