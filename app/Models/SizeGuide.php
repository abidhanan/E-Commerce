<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SizeGuide extends Model
{
    protected $fillable = [
        'type',
        'name',
        'img',
        'data'
    ];

    protected $casts = [
        'data' => 'array',
    ];
}