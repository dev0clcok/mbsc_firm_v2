<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'phone' => '+880 1868-196716',
            'whatsapp' => '+880 1868-196716',
            'whatsapp_message' => 'Hello MBSC Firm, I would like to ask about your services.',
            'email' => 'mbscfirm@gmail.com',
            'enquiry_email' => 'mbscfirm@gmail.com',
            'address' => 'Burma Razu Building (2nd Floor), beside Fancy Tailors, Kotowali, Chattogram, Bangladesh',
            'maps_url' => null,
            'office_hours' => 'Saturday to Thursday, 10:00 AM to 7:00 PM',
            'facebook_url' => 'https://www.facebook.com/profile.php?id=61578616047092',
            'linkedin_url' => null,
            'x_url' => null,
            'youtube_url' => null,
            'instagram_url' => null,
            // A starting draft. It stays off the website until it is marked as reviewed in the admin.
            'privacy_policy' => file_get_contents(__DIR__.'/content/privacy-policy.md'),
            'privacy_published' => '0',
        ];

        // Only fill in settings that have never been saved, so re-seeding
        // does not overwrite what was entered in the admin panel.
        $existing = SiteSetting::query()->pluck('key')->all();

        SiteSetting::put(array_diff_key($defaults, array_flip($existing)));

        // Starter pictures, listed in CREDITS.md. Replace them in the admin panel.
        $heroes = [
            'hero_home' => ['url' => '/images/seed/signing-documents-1280.webp', 'width' => 1280, 'height' => 854, 'alt' => 'Hands signing a paper document'],
            'hero_services' => ['url' => '/images/seed/office-towers-1280.webp', 'width' => 1280, 'height' => 854, 'alt' => 'Office towers seen from street level'],
            'hero_about' => ['url' => '/images/seed/office-interior-1280.webp', 'width' => 1280, 'height' => 854, 'alt' => 'Empty office with glass partitions'],
        ];

        foreach (array_diff_key($heroes, array_flip($existing)) as $key => $image) {
            SiteSetting::putImage($key, $image);
        }
    }
}
