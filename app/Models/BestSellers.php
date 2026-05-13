<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BestSellers extends Model
{
    protected $fillable = [
        'product_id',
        'position',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'position' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
