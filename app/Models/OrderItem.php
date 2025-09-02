<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Modelo OrderItem: representa un producto específico dentro de un pedido
class OrderItem extends Model
{
    use HasFactory;

    // Nombre de la tabla (opcional, Laravel lo infiere como "order_items")
    protected $table = 'order_items';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'order_id',          // ID del pedido al que pertenece
        'product_id',        // ID del producto comprado
        'product_name',      // Nombre del producto (copiado en el momento del pedido)
        'product_price',     // Precio unitario al momento del pedido
        'quantity',          // Cantidad comprada
        'total',             // Total por ítem (price * quantity)
    ];

    // Relación: el ítem pertenece a un pedido
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relación: el ítem está asociado a un producto
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

