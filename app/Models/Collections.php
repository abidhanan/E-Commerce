<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collections extends Model
{
    protected $fillable = ['name','slug','img'];
   
    public function products()
    {
        return $this->hasMany(Product::class,'collection_id');
    }
}
