<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgressStep extends Model
{
    protected $fillable = [
        'module',
        'title',
        'slug',
        'description',
        'step_order',
        'is_active',
    ];
}