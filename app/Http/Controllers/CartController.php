<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;   // ✅ importa el modelo
use App\Models\CartItem; // ✅ importa el modelo
use App\Models\User; // ✅ importa el modelo
use Illuminate\Support\Facades\Auth;

// app/Http/Controllers/CartController.php
class CartController extends Controller
{
    public function __construct()
{

}
    public function index()
    {
    
        $cartItems = CartItem::where('user_id', Auth::id())
            ->with('product')
            ->get();
        
        $subtotal = $cartItems->sum(function($item) {
            return $item->quantity * $item->price;
        });
        
        $tax = $subtotal * 0.16; // 16% IVA
        $total = $subtotal + $tax;
        
        return view('cart.index', compact('cartItems', 'subtotal', 'tax', 'total'));
    }

    public function add(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        
        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();
        
        if ($cartItem) {
            $cartItem->increment('quantity', $request->quantity ?? 1);
        } else {
            CartItem::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $request->quantity ?? 1,
                'price' => $product->final_price
            ]);
        }
        
        return redirect()->back()->with('success', 'Producto agregado al carrito');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $cartItem->update(['quantity' => $request->quantity]);
        return redirect()->back();
    }

    public function remove(CartItem $cartItem)
    {
        $cartItem->delete();
        return redirect()->back();
    }
}