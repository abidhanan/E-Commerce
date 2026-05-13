<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomCollectionsDisplay extends Model
{
    protected $fillable = [
        'collection_id',
        'product_id',
        'position',
    ];

    protected $casts = [
        'collection_id' => 'integer',
        'product_id' => 'integer',
        'position' => 'integer',
    ];

    public function collection()
    {
        return $this->belongsTo(Collections::class, 'collection_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function products(){
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function scopeActive($query)
    {
        return $query->whereHas('product', function ($q) {
            $q->where('is_active', true);
        });
    }
}