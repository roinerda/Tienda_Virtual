<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'discount_price',
        'stock',
        'sku',
        'images',
        'category_id',
        'active',
        'featured'
    ];

    protected $casts = [
        'images'         => 'array',      // Para que siempre sea un array
        'active'         => 'boolean',    // Para tratarlo como boolean
        'featured'       => 'boolean',    // Para tratarlo como boolean
        'price'          => 'decimal:2',  // Para mostrar con 2 decimales
        'discount_price' => 'decimal:2'   // Para mostrar con 2 decimales
    ];

    /* ==========================
       Relaciones
    ========================== */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /* ==========================
       Configuración de rutas
    ========================== */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /* ==========================
       Accesores / Helpers
    ========================== */
    public function getDiscountPercentageAttribute()
    {
        if ($this->discount_price && $this->price > 0) {
            return round((($this->price - $this->discount_price) / $this->price) * 100);
        }
        return 0;
    }

    public function getFinalPriceAttribute()
    {
        return $this->discount_price ?? $this->price;
    }
}