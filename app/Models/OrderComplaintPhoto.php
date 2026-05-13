<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderComplaintPhoto extends Model
{
    protected $fillable = [
        'order_complaint_id',
        'path',
    ];

    public function complaint()
    {
        return $this->belongsTo(OrderComplaint::class, 'order_complaint_id');
    }
}
