<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_code',
        'user_id',
        'address_id',
        'subtotal',
        'shipping_cost',
        'gross_amount',
        'status',
        'stock_deducted_at',
        'snap_token',
        'payment_url',
        'customer_note',
        'admin_note',
        'quoted_at',
        'shipped_at',
        'delivery_estimated_at',
        'completed_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function review()
    {
        return $this->hasOne(OrderReview::class);
    }

    public function complaints()
    {
        return $this->hasMany(OrderComplaint::class);
    }

    protected function casts(): array
    {
        return [
            'quoted_at' => 'datetime',
            'stock_deducted_at' => 'datetime',
            'shipped_at' => 'datetime',
            'delivery_estimated_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }
}
