<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Breathability extends Model
{
    protected $fillable = [
        'level',
        'label',
        'description',
    ];
}