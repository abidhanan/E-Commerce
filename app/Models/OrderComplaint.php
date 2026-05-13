<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderComplaint extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'subject',
        'message',
        'status',
        'admin_response',
        'resolved_at',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function photos()
    {
        return $this->hasMany(OrderComplaintPhoto::class);
    }

    protected function casts(): array
    {
        return [
            'resolved_at' => 'datetime',
        ];
    }
}
