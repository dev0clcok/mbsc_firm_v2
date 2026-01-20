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
                'question' => 'How much do Bangladesh Consultant financial consulting services cost?',
                'answer' => 'Our consulting service fees depend on the complexities, duration and workload of the services you require. However, we offer hourly services, flat fee packages, retainer arrangements and customized plans to meet your needs. Rest assured, whichever plan you opt for, it would be highly competitive.',
                'category' => 'General',
                'service_id' => null,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'question' => 'What are the most common financial challenges businesses face in Bangladesh?',
                'answer' => 'The most common financial challenges faced by businesses in Bangladesh include lack of funding particularly for SMEs, probably because the banks provide lower credit limits or high interest rates. Moreover, cash-flow mismanagement is also another problem most Bangladeshi businesses suffer from, especially businesses with long payment cycles or based on seasonal business. The businesses also have issues with taxation and regulatory compliance either due to lack of knowledge or because the process is tedious and confusing which leads to penalties.',
                'category' => 'General',
                'service_id' => null,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'question' => 'How does Bangladesh Consultant assist with drafting the MOA and AOA?',
                'answer' => 'We provide expert guidance to draft the Memorandum of Association (MOA) and Articles of Association (AOA), ensuring compliance with Bangladesh\'s legal requirements and alignment with your business goals.',
                'category' => 'Company Registration',
                'service_id' => null,
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'question' => 'Does Bangladesh Consultant use any advanced tools or technologies?',
                'answer' => 'Yes, we take the fullest advantages of modern technologies and software to ensure the highest quality of service to our clients. We utilize leading-edge software for financial services, data analysis, and of course Artificial Intelligence for providing streamlined financial service, optimized decisions and tailored solutions to our clients.',
                'category' => 'General',
                'service_id' => null,
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'question' => 'Why choose Bangladesh Consultant over other consulting firms?',
                'answer' => 'We offer local expertise, global standards, comprehensive services, and a proven track record, all tailored to your unique business needs.',
                'category' => 'General',
                'service_id' => null,
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'question' => 'Can Bangladesh Consultant assist both startups and established businesses?',
                'answer' => 'Yes, we support startups with registration & compliance and help established businesses with tax optimization, compliance, and expansion strategies.',
                'category' => 'General',
                'service_id' => null,
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'question' => 'How does Bangladesh Consultant help with tax and VAT compliance?',
                'answer' => 'We handle TIN and VAT registration, prepare and file returns, optimize tax liabilities, and represent you during audits.',
                'category' => 'Tax & VAT',
                'service_id' => null,
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'question' => 'Does Bangladesh Consultant offer corporate secretarial services?',
                'answer' => 'Yes, we provide services like maintaining statutory records, filing annual returns, organizing meetings, and ensuring corporate governance compliance.',
                'category' => 'Secretarial Services',
                'service_id' => null,
                'sort_order' => 8,
                'is_active' => true,
            ],
            [
                'question' => 'Can I get ongoing support after project completion?',
                'answer' => 'Yes, we offer continuous support, including compliance monitoring, regulatory updates, and strategic advice for long-term success.',
                'category' => 'General',
                'service_id' => null,
                'sort_order' => 9,
                'is_active' => true,
            ],
            [
                'question' => 'Can you assist with cross-border tax compliance or international expansion?',
                'answer' => 'Yes, we help with cross-border tax compliance, double taxation agreements, and international business setup and expansion strategies.',
                'category' => 'Tax & VAT',
                'service_id' => null,
                'sort_order' => 10,
                'is_active' => true,
            ],
        ];

        foreach ($faqs as $faq) {
            FAQ::firstOrCreate(
                ['question' => $faq['question']],
                $faq
            );
        }
    }
}
