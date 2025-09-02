<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CartItem
 *
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property int $quantity
 * @property float $price
 * @property \App\Models\User $user
 * @property \App\Models\Product $product
 */
class CartItem extends Model
{
    use HasFactory;

    // Nombre de la tabla 
    protected $table = 'cart_items';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'price',
    ];

    // Relación: el ítem pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación: el ítem pertenece a un producto
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
