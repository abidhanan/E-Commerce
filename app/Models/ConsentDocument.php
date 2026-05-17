<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsentDocument extends Model
{
    public const TYPE_TERMS_PRIVACY = 'terms_privacy';
    public const TYPE_NEWSLETTER = 'newsletter';

    protected $fillable = [
        'type',
        'slug',
        'title',
        'summary',
        'content',
        'is_active',
        'position',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'position' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('position')->orderBy('title');
    }

    public static function typeOptions(): array
    {
        return [
            self::TYPE_TERMS_PRIVACY => 'Terms, Conditions & Privacy',
            self::TYPE_NEWSLETTER => 'Newsletter & Offers',
        ];
    }

    public function getTypeLabelAttribute(): string
    {
        return self::typeOptions()[$this->type] ?? ucfirst(str_replace('_', ' ', $this->type));
    }
}
