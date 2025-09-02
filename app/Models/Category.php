<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

// Modelo Category: representa una categoría de productos
class Category extends Model
{
    // Campos que se pueden asignar masivamente
    protected $fillable = ['name', 'slug', 'description', 'image', 'active'];

    // Relación: una categoría tiene muchos productos
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Permite usar el slug en lugar del ID en las rutas (Route Model Binding)
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
