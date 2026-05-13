<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrashReplacement extends Model
{
    protected $fillable = [
        'question',
        'answer',
        'position',
        'is_active',
    ];
}