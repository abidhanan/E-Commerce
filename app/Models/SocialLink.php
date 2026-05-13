<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    public const TYPE_SOCIAL = 'social';
    public const TYPE_MARKETPLACE = 'marketplace';

    protected $fillable = [
        'type',
        'platform',
        'label',
        'url',
        'position',
        'is_active',
    ];

    protected $casts = [
        'position' => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('type')->orderBy('position')->orderBy('label');
    }

    public static function typeOptions(): array
    {
        return [
            self::TYPE_SOCIAL => 'Sosial Media',
            self::TYPE_MARKETPLACE => 'Official Store',
        ];
    }

    public static function platformOptions(?string $type = null): array
    {
        $options = [
            self::TYPE_SOCIAL => [
                'instagram' => 'Instagram',
                'youtube' => 'YouTube',
                'tiktok' => 'TikTok',
                'facebook' => 'Facebook',
                'x' => 'X / Twitter',
                'linkedin' => 'LinkedIn',
                'whatsapp' => 'WhatsApp',
                'telegram' => 'Telegram',
                'strava' => 'Strava',
                'custom' => 'Custom Social',
            ],
            self::TYPE_MARKETPLACE => [
                'shopee' => 'Shopee',
                'tokopedia' => 'Tokopedia',
                'zalora' => 'Zalora',
                'tiktok_shop' => 'TikTok Shop',
                'custom' => 'Custom Store',
            ],
        ];

        if ($type === null) {
            return $options;
        }

        return $options[$type] ?? [];
    }

    public static function flattenedPlatformOptions(): array
    {
        return array_merge(
            self::platformOptions(self::TYPE_SOCIAL),
            self::platformOptions(self::TYPE_MARKETPLACE)
        );
    }

    public static function labelForPlatform(string $platform): string
    {
        return self::flattenedPlatformOptions()[$platform] ?? ucfirst(str_replace('_', ' ', $platform));
    }

    public function getDisplayLabelAttribute(): string
    {
        return $this->label ?: self::labelForPlatform($this->platform);
    }
}