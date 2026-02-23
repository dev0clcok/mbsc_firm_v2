<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Mohammad Rahman',
                'position' => 'CEO',
                'company' => 'TechStart BD',
                'text' => 'Outstanding service! They handled our VAT compliance with utmost professionalism. Highly recommended for any business.',
                'rating' => 5,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Fatima Ahmed',
                'position' => 'Director',
                'company' => 'Green Exports Ltd',
                'text' => "The team's expertise in IRC/ERC processing saved us weeks of work. Their knowledge of regulations is exceptional.",
                'rating' => 5,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Karim Hassan',
                'position' => 'Managing Director',
                'company' => 'Dhaka Industries',
                'text' => 'Professional, responsive, and knowledgeable. They made our BIDA registration process smooth and hassle-free.',
                'rating' => 5,
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::firstOrCreate(
                ['name' => $testimonial['name'], 'company' => $testimonial['company']],
                $testimonial
            );
        }
    }
}
