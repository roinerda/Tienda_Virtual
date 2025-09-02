<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Modelo Order: representa un pedido realizado por un usuario
class Order extends Model
{
    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'order_number', 'user_id', 'subtotal', 'tax_amount',
        'shipping_amount', 'total_amount', 'status', 'payment_status',
        'payment_method', 'billing_address', 'shipping_address'
    ];

    // Conversión automática de tipos al acceder a los atributos
    protected $casts = [
        'billing_address' => 'array',           // Dirección de facturación como array
        'shipping_address' => 'array',          // Dirección de envío como array
        'subtotal' => 'decimal:2',              // Subtotal con 2 decimales
        'tax_amount' => 'decimal:2',            // Impuestos con 2 decimales
        'shipping_amount' => 'decimal:2',       // Envío con 2 decimales
        'total_amount' => 'decimal:2'           // Total con 2 decimales
    ];

    // Relación: el pedido pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación: el pedido tiene múltiples ítems
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Hook para generar automáticamente el número de pedido al crear
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->order_number = 'ORD-' . strtoupper(uniqid()); // Genera un identificador único
        });
    }
}