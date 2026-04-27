<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagBlog extends Model
{
    protected $table = 'tag_blogs';

    protected $fillable = [
        'name',
    ];
}
