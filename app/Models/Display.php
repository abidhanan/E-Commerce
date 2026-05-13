<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Display extends Model
{
    protected $fillable = [
    'image_1_path',
    'image_1_title',
    'image_1_sub_title',

    'image_2_path',
    'image_2_title',
    'image_2_sub_title',

    'image_3_path',
    'image_3_title',
    'image_3_sub_title',

    'running_text',
    ];
    
}