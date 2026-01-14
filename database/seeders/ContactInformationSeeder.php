<?php

namespace Database\Seeders;

use App\Models\ContactInformation;
use Illuminate\Database\Seeder;

class ContactInformationSeeder extends Seeder
{
    public function run(): void
    {
        ContactInformation::truncate();
        
        $contactInfo = [
            [
                'type' => 'email',
                'label' => 'Email',
                'value' => 'mbscfirm@gmail.com',
                'is_primary' => true,
                'sort_order' => 1,
            ],
            [
                'type' => 'phone',
                'label' => 'Phone',
                'value' => '+88 01868-196716',
                'is_primary' => true,
                'sort_order' => 2,
            ],
            [
                'type' => 'address',
                'label' => 'Office Address',
                'value' => 'Burma Razu Building (02nd Floor), Besides Fancy Tailors, Kotowali, Chattogram',
                'is_primary' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($contactInfo as $info) {
            ContactInformation::firstOrCreate(
                ['type' => $info['type'], 'value' => $info['value']],
                $info
            );
        }
    }
}
