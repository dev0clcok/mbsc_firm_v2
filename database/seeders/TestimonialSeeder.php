<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $services = Service::all()->keyBy('slug');

        $testimonials = [
            [
                'client_name' => 'Ahmed Rahman',
                'client_position' => 'CEO',
                'client_company' => 'Tech Solutions BD Ltd.',
                'testimonial' => 'Bangladesh Consultant made our company registration process incredibly smooth. Their expertise and attention to detail saved us weeks of time. Highly recommended for anyone looking to start a business in Bangladesh.',
                'rating' => 5,
                'service_slug' => 'company-registration',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'client_name' => 'Fatima Khan',
                'client_position' => 'Finance Director',
                'client_company' => 'Global Manufacturing Inc.',
                'testimonial' => 'The payroll services provided by Bangladesh Consultant have been exceptional. They handle everything professionally and ensure full compliance. Our team can focus on business growth while they manage our payroll efficiently.',
                'rating' => 5,
                'service_slug' => 'payroll-services',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'client_name' => 'Mohammad Hassan',
                'client_position' => 'Managing Director',
                'client_company' => 'Export Trading Co.',
                'testimonial' => 'Their tax and VAT compliance services are outstanding. They helped us navigate complex regulations and saved us significant time and money. The team is knowledgeable, responsive, and always available when we need them.',
                'rating' => 5,
                'service_slug' => 'tax-vat-compliance',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'client_name' => 'Sara Ahmed',
                'client_position' => 'HR Manager',
                'client_company' => 'Digital Services Ltd.',
                'testimonial' => 'We\'ve been using their accounting services for over two years now. The quality of work is consistently excellent, and they provide valuable insights that help us make better financial decisions.',
                'rating' => 5,
                'service_slug' => 'accounting-services',
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'client_name' => 'Rashid Ali',
                'client_position' => 'Operations Manager',
                'client_company' => 'Construction Group',
                'testimonial' => 'The secretarial services team is professional and thorough. They ensure all our corporate compliance requirements are met on time, every time. It\'s a relief to have such reliable partners.',
                'rating' => 5,
                'service_slug' => 'secretarial-services',
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'client_name' => 'Nadia Islam',
                'client_position' => 'Founder',
                'client_company' => 'Startup Ventures',
                'testimonial' => 'As a startup, we needed help with licensing and compliance. Bangladesh Consultant guided us through every step, making what seemed complicated very straightforward. Their support has been invaluable.',
                'rating' => 5,
                'service_slug' => 'licensing',
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'client_name' => 'Karim Uddin',
                'client_position' => 'CFO',
                'client_company' => 'Financial Services Group',
                'testimonial' => 'The actuarial valuation services provided were comprehensive and accurate. Their expertise helped us properly structure our employee benefits program. Excellent service delivery.',
                'rating' => 5,
                'service_slug' => 'actuarial-valuation',
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'client_name' => 'Laila Chowdhury',
                'client_position' => 'Director',
                'client_company' => 'Trading Corporation',
                'testimonial' => 'Their auditing services are thorough and professional. The audit reports are detailed and provide clear recommendations for improvement. We trust them completely with our financial audits.',
                'rating' => 5,
                'service_slug' => 'auditing-services',
                'is_featured' => false,
                'is_active' => true,
            ],
        ];

        foreach ($testimonials as $testimonialData) {
            $serviceSlug = $testimonialData['service_slug'] ?? null;
            unset($testimonialData['service_slug']);

            $service = $serviceSlug && isset($services[$serviceSlug]) 
                ? $services[$serviceSlug] 
                : null;

            Testimonial::firstOrCreate(
                [
                    'client_name' => $testimonialData['client_name'],
                    'client_company' => $testimonialData['client_company'],
                ],
                [
                    ...$testimonialData,
                    'service_id' => $service?->id,
                ]
            );
        }
    }
}
