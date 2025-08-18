<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

// app/Models/Category.php
class Category extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'image', 'active'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
   



}
