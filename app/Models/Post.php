<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'tag_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'thumbnail',
        'status',
        'published_at',
    ];
    protected $appends = ['tags'];
    public function category()
    {
        return $this->belongsTo(CategoryBlog::class, 'category_id');
    }

    public function getTagsAttribute()
    {
        return TagBlog::whereIn('id', $this->tag_id ?? [])->get();
    }
    protected $casts = [
        'tag_id' => 'array',
        'published_at' => 'datetime',
    ];

    public function getTagObjectsAttribute()
    {
        return \App\Models\TagBlog::whereIn('id', $this->tag_id ?? [])->get();
    }
}