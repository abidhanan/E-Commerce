<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisplayLogin extends Model
{
    protected $fillable = [
        'label',
        'image_path',
        'position',
    ];
}