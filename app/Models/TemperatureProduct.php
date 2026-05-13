<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemperatureProduct extends Model
{
    protected $fillable = [
        'min_temperature',
        'max_temperature',
        'label',
        'description',
    ];
}