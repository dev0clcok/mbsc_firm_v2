<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'phone' => '+88 01868-196716',
            'whatsapp' => '+88 01868-196716',
            'email' => 'mbscfirm@gmail.com',
            'enquiry_email' => 'mbscfirm@gmail.com',
            'address' => 'Burma Razu Building (2nd Floor), beside Fancy Tailors, Kotowali, Chattogram, Bangladesh',
            'maps_url' => null,
            'office_hours' => 'Saturday to Thursday, 10:00 AM to 7:00 PM',
            'response_time' => null,
            'facebook_url' => 'https://www.facebook.com/profile.php?id=61578616047092',
            'linkedin_url' => null,
            'x_url' => null,
            'youtube_url' => null,
            'instagram_url' => null,
        ];

        // Only fill in settings that have never been saved, so re-seeding
        // does not overwrite what was entered in the admin panel.
        $existing = SiteSetting::query()->pluck('key')->all();

        SiteSetting::put(array_diff_key($defaults, array_flip($existing)));
    }
}
