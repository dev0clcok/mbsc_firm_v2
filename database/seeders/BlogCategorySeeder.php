<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use Illuminate\Database\Seeder;

class BlogCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Company Registration',
                'slug' => 'company-registration',
                'description' => 'Articles about company registration in Bangladesh',
            ],
            [
                'name' => 'Tax & Compliance',
                'slug' => 'tax-compliance',
                'description' => 'Tax and compliance related articles',
            ],
            [
                'name' => 'Business News',
                'slug' => 'business-news',
                'description' => 'Latest business news and updates',
            ],
            [
                'name' => 'Legal Updates',
                'slug' => 'legal-updates',
                'description' => 'Legal updates and changes in Bangladesh',
            ],
        ];

        foreach ($categories as $category) {
            BlogCategory::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
