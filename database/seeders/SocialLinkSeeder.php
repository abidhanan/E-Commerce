<?php

namespace Database\Seeders;

use App\Models\SocialLink;
use Illuminate\Database\Seeder;

class SocialLinkSeeder extends Seeder
{
    public function run(): void
    {
        $links = [
            [
                'type' => SocialLink::TYPE_SOCIAL,
                'platform' => 'instagram',
                'label' => 'Instagram',
                'url' => 'https://instagram.com/gloamingimagine',
                'position' => 1,
                'is_active' => true,
            ],
            [
                'type' => SocialLink::TYPE_SOCIAL,
                'platform' => 'tiktok',
                'label' => 'TikTok',
                'url' => 'https://tiktok.com/@gloamingimagine',
                'position' => 2,
                'is_active' => true,
            ],
            [
                'type' => SocialLink::TYPE_MARKETPLACE,
                'platform' => 'shopee',
                'label' => 'Shopee',
                'url' => 'https://shopee.co.id/gloamingimagine',
                'position' => 1,
                'is_active' => true,
            ],
            [
                'type' => SocialLink::TYPE_MARKETPLACE,
                'platform' => 'tokopedia',
                'label' => 'Tokopedia',
                'url' => 'https://www.tokopedia.com/gloamingimagine',
                'position' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($links as $link) {
            SocialLink::query()->updateOrCreate(
                [
                    'type' => $link['type'],
                    'platform' => $link['platform'],
                ],
                $link,
            );
        }
    }
}
