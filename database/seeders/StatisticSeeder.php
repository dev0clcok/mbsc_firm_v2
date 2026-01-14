<?php

namespace Database\Seeders;

use App\Models\Statistic;
use Illuminate\Database\Seeder;

class StatisticSeeder extends Seeder
{
    public function run(): void
    {
        $statistics = [
            [
                'label' => 'Consulting Success',
                'value' => '98%',
                'icon' => 'trending-up',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'label' => 'Worldwide Clients',
                'value' => '120+',
                'icon' => 'users',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'label' => 'Business Advices Given',
                'value' => '500+',
                'icon' => 'lightbulb',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'label' => 'Business Excellence Awards',
                'value' => '30+',
                'icon' => 'award',
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($statistics as $statistic) {
            Statistic::firstOrCreate(
                ['label' => $statistic['label']],
                $statistic
            );
        }
    }
}
