<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CareGuide extends Model
{
    protected $fillable = [
        'question',
        'answer',
        'position',
        'is_active',
    ];
}