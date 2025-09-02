<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\CartItem;
use App\Models\OrderItem;

// Modelo Product: representa un producto disponible en la tienda
class Product extends Model
{
    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'name',               // Nombre del producto
        'slug',               // Slug para URL amigable
        'description',        // Descripción larga
        'short_description',  // Descripción corta
        'price',              // Precio original
        'discount_price',     // Precio con descuento (opcional)
        'stock',              // Cantidad disponible
        'sku',                // Código único del producto
        'images',             // Array de rutas de imágenes
        'category_id',        // Relación con categoría
        'active',             // Estado de visibilidad
        'featured'            // Destacado en la tienda
    ];

    // Conversión automática de tipos
    protected $casts = [
        'images'         => 'array',      // Asegura que siempre sea array
        'active'         => 'boolean',    // Para usar como flag
        'featured'       => 'boolean',    // Para destacar productos
        'price'          => 'decimal:2',  // Formato monetario
        'discount_price' => 'decimal:2'   // Formato monetario
    ];

    /* ==========================
       Relaciones
    ========================== */

    // Relación: el producto pertenece a una categoría
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relación: el producto puede estar en múltiples carritos
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    // Relación: el producto puede aparecer en múltiples pedidos
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    

    // Permite usar el slug en lugar del ID en las rutas
    public function getRouteKeyName()
    {
        return 'slug';
    }

    

    // Calcula el porcentaje de descuento aplicado
    public function getDiscountPercentageAttribute()
    {
        if ($this->discount_price && $this->price > 0) {
            return round((($this->price - $this->discount_price) / $this->price) * 100);
        }
        return 0;
    }

    // Devuelve el precio final (con descuento si aplica)
    public function getFinalPriceAttribute()
    {
        return $this->discount_price ?? $this->price;
    }
}
