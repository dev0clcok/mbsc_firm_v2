<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class TeamMemberSeeder extends Seeder
{
    public function run(): void
    {
        // Only real people belong here. Add other members in the admin panel.
        $members = [
            [
                'name' => 'Mr. S. M. Sirajul Monir (Rifat)',
                'position' => 'Founder & Consultant',
                'specialization' => 'Corporate Law & Taxation',
                'image_url' => '/images/founder.webp',
                'sort_order' => 1,
                'is_active' => true,
                'social_links' => [
                    [
                        'platform' => 'linkedin',
                        'url' => 'https://www.linkedin.com/in/rifat-s-m-sirajul-monir-b1925b192/',
                    ],
                    [
                        'platform' => 'facebook',
                        'url' => 'https://www.facebook.com/rifat.s.m.sirajul.monir',
                    ],
                    [
                        'platform' => 'twitter',
                        'url' => 'https://twitter.com/rifat_s_m_sirajul_monir',
                    ],
                ],
            ],
        ];

        foreach ($members as $data) {
            $socialLinks = $data['social_links'] ?? [];
            unset($data['social_links']);

            $member = TeamMember::firstOrCreate(
                ['name' => $data['name']],
                $data
            );

            if ($member->wasRecentlyCreated && ! empty($socialLinks)) {
                foreach ($socialLinks as $i => $link) {
                    $member->socialLinks()->create([
                        'platform' => $link['platform'],
                        'url' => $link['url'],
                        'sort_order' => $i,
                    ]);
                }
            }
        }
    }
}
