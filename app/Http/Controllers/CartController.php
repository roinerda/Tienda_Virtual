<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\Order;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::with('product')
            ->where('user_id', Auth::id())
            ->get();

        $subtotal = $cartItems->sum(fn($item) => $item->quantity * $item->price);
        $tax = $subtotal * 0.16; // IVA 16%
        $shipping = 2500; // Envío fijo
        $total = $subtotal + $tax + $shipping;

        return view('cart.index', compact('cartItems', 'subtotal', 'tax', 'shipping', 'total'));
    }

    public function add(Request $request)
    {
        $product = Product::findOrFail($request->product_id);

        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        $quantity = $request->quantity ?? 1;

        if ($quantity > $product->stock) {
            return redirect()->back()->with('error', 'No hay suficiente stock disponible');
        }

        if ($cartItem) {
            $newQty = $cartItem->quantity + $quantity;
            $cartItem->update([
                'quantity' => min($newQty, $product->stock)
            ]);
        } else {
            CartItem::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->final_price
            ]);
        }

        return redirect()->back()->with('success', 'Producto agregado al carrito');
    }

    public function update(Request $request, $cartItemId = null)
    {
        if ($cartItemId === 'bulk') {
            foreach ($request->quantities as $id => $qty) {
                $item = CartItem::where('user_id', Auth::id())->find($id);
                if ($item && $qty > 0 && $qty <= $item->product->stock) {
                    $item->update(['quantity' => $qty]);
                }
            }
            return redirect()->back()->with('success', 'Cantidades actualizadas');
        }

        $cartItem = CartItem::where('user_id', Auth::id())->findOrFail($cartItemId);
        $qty = $request->quantity;

        if ($qty > 0 && $qty <= $cartItem->product->stock) {
            $cartItem->update(['quantity' => $qty]);
        }

        return redirect()->back()->with('success', 'Cantidad actualizada');
    }

    public function remove($id)
{
    $cartItem = CartItem::where('user_id', Auth::id())->find($id);

    if (!$cartItem) {
        return redirect()->back()->with('error', 'No se encontró el producto en tu carrito');
    }

    $cartItem->delete();

    return redirect()->back()->with('success', 'Producto eliminado del carrito');
}



    public function clear()
    {
        CartItem::where('user_id', Auth::id())->delete();
        return redirect()->back()->with('success', 'Carrito vaciado');
    }

    public function finalize(Request $request)
    {
        $userId = Auth::id();
        $cartItems = CartItem::with('product')->where('user_id', $userId)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío');
        }

        $subtotal = $cartItems->sum(fn($item) => $item->quantity * $item->price);
        $tax = round($subtotal * 0.16, 2);
        $shipping = 2500;
        $total = $subtotal + $tax + $shipping;

        $order = Order::create([
            'user_id' => $userId,
            'order_number' => 'ORD-' . strtoupper(Str::random(12)),
            'subtotal' => $subtotal,
            'tax_amount' => $tax,
            'shipping_amount' => $shipping,
            'total_amount' => $total,
            'status' => 'pendiente',
            'payment_status' => 'pendiente',
            'payment_method' => $request->payment_method ?? 'tarjeta',
            'billing_address' => (object)[],
            'shipping_address' => (object)[],

        ]);

        foreach ($cartItems as $item) {
            $order->orderItems()->create([
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'product_price' => $item->price,
                'quantity' => $item->quantity,
                'total' => $item->price * $item->quantity,
            ]);
        }

        CartItem::where('user_id', $userId)->delete();

        return redirect()->route('orders.show', $order->id)->with('success', 'Pedido confirmado');
    }
}
