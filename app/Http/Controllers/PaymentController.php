<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function process(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:credit_card,paypal',
            'billing_address' => 'required|array',
            'shipping_address' => 'required|array'
        ]);
        
       $cartItems = CartItem::where('user_id', Auth::id())->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'El carrito está vacío');
        }
        
        $subtotal = $cartItems->sum(function($item) {
            return $item->quantity * $item->price;
        });
        
        $tax = $subtotal * 0.16;
        $shipping = 50; // Costo fijo de envío
        $total = $subtotal + $tax + $shipping;
        
        // Crear pedido
        $order = Order::create([
            'user_id' => Auth::id(),
            'subtotal' => $subtotal,
            'tax_amount' => $tax,
            'shipping_amount' => $shipping,
            'total_amount' => $total,
            'payment_method' => $request->payment_method,
            'billing_address' => $request->billing_address,
            'shipping_address' => $request->shipping_address,
            'status' => 'processing',
            'payment_status' => 'paid'
        ]);
        
        // Crear items del pedido
        foreach ($cartItems as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'product_price' => $item->price,
                'quantity' => $item->quantity,
                'total' => $item->quantity * $item->price
            ]);
        }
        
        // Limpiar carrito
        $cartItems->each->delete();
        
        return redirect()->route('orders.show', $order)->with('success', 'Pedido realizado exitosamente');
    }
}
