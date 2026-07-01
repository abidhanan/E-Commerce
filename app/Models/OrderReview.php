<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderReview extends Model
{
    protected $fillable = [
        'order_id',
        'product_id', // INI KUNCI UTAMA YANG KAMU LEWATKAN
        'user_id',
        'rating',
        'comment',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // RELASI INI WAJIB ADA AGAR PRODUK DAN ULASAN SALING MENGENAL
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}