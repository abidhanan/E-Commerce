<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'category_id', 'collection_id', 'size_guide_id', 'name', 'slug', 'description',
        'material', 'gender', 'weight', 'is_active',
        'temperature', 'intensity', 'insulation', 'breathability',
    ];

    public function category()
    {
        return $this->belongsTo(CategoryProduct::class, 'category_id');
    }

    public function collection()
    {
        return $this->belongsTo(Collections::class, 'collection_id');
    }
    
    public function customCollections()
    {
        return $this->belongsToMany(Collections::class, 'custom_collections_displays', 'product_id', 'collection_id')
            ->withPivot('position')
            ->withTimestamps();
    }
    
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function sizeGuide()
    {
        return $this->belongsTo(SizeGuide::class, 'size_guide_id');
    }

    public function wishlistedByUsers()
    {
        return $this->belongsToMany(User::class, 'wishlists')
            ->withTimestamps();
    }

    protected $casts = [
        'material' => 'array',
    ];

    // ACCESSOR (ambil data material)
    public function getMaterialAttribute($value)
    {
        if (is_null($value)) {
            return [];
        }

        if (is_array($value)) {
            return $value;
        }

        if ($this->isJson($value)) {
            return json_decode($value, true) ?? [];
        }

        return [$value];
    }

    // MUTATOR (simpan data material)
    public function setMaterialAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['material'] = json_encode($value);
        } else {
            $this->attributes['material'] = json_encode([$value]);
        }
    }

    // Helper validasi JSON
    private function isJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Relasi langsung ke ulasan. 
     * Eksekusi kueri yang jauh lebih ringan dan anti N+1 saat di-eager load.
     */
    public function verifiedReviews()
    {
        return $this->hasMany(OrderReview::class, 'product_id')->latest();
    }
}