<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/Order.php
class Order extends Model
{
    protected $fillable = [
        'order_number', 'user_id', 'subtotal', 'tax_amount',
        'shipping_amount', 'total_amount', 'status', 'payment_status',
        'payment_method', 'billing_address', 'shipping_address'
    ];

    protected $casts = [
        'billing_address' => 'array',
        'shipping_address' => 'array',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'total_amount' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            $order->order_number = 'ORD-' . strtoupper(uniqid());
        });
    }
}
