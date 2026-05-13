<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
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
        'material' => 'array', // tetap pakai ini
    ];

    // ACCESSOR (ambil data)
    public function getMaterialAttribute($value)
    {
        if (is_null($value)) {
            return [];
        }

        // kalau sudah array (JSON)
        if (is_array($value)) {
            return $value;
        }

        // kalau string JSON "[1,2]"
        if ($this->isJson($value)) {
            return json_decode($value, true) ?? [];
        }

        // fallback string biasa "1"
        return [$value];
    }

    // MUTATOR (simpan data)
    public function setMaterialAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['material'] = json_encode($value);
        } else {
            $this->attributes['material'] = json_encode([$value]);
        }
    }

    // helper
    private function isJson($string)
    {
        json_decode($string);

        return json_last_error() === JSON_ERROR_NONE;
    }
}