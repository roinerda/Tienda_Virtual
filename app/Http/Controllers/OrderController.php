<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    // Mostrar lista de pedidos del usuario
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->latest()->get();
        return view('orders.index', compact('orders'));
    }

    // Mostrar detalle de un pedido
    public function show(Order $order)
    {
        // Asegurarse de que el pedido pertenece al usuario
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('orders.show', compact('order'));
    }

    // Crear un nuevo pedido
    public function store(Request $request)
    {
        $userId = Auth::id();
        $cartItems = \App\Models\CartItem::with('product')->where('user_id', $userId)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío');
        }

        $subtotal = $cartItems->sum(fn($item) => $item->quantity * $item->price);
        $tax = round($subtotal * 0.16, 2);
        $shipping = 2500;
        $total = $subtotal + $tax + $shipping;

        $order = Order::create([
            'user_id' => $userId,
            'order_number' => 'ORD-' . strtoupper(\Illuminate\Support\Str::random(12)),
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

        \App\Models\CartItem::where('user_id', $userId)->delete();

        return redirect()->route('orders.show', $order->id)->with('success', 'Pedido creado correctamente.');
    }

    // Cancelar un pedido
    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->update(['status' => 'cancelado']);

        return redirect()->route('orders.index')->with('success', 'Pedido cancelado.');
    }

    public function payment(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('orders.payment', compact('order'));
    }
    public function confirmPayment(Request $request, Order $order)
{
    if ($order->user_id !== Auth::id()) {
        abort(403);
    }

    $validated = $request->validate([
        'payment_method' => 'required|in:tarjeta,paypal,transferencia',
        'billing_address.calle' => 'nullable|string|max:255',
        'billing_address.ciudad' => 'nullable|string|max:255',
        'billing_address.provincia' => 'nullable|string|max:255',
        'billing_address.codigo_postal' => 'nullable|string|max:20',
        'shipping_address.calle' => 'nullable|string|max:255',
        'shipping_address.ciudad' => 'nullable|string|max:255',
        'shipping_address.provincia' => 'nullable|string|max:255',
        'shipping_address.codigo_postal' => 'nullable|string|max:20',
    ]);

    $order->update([
        'payment_status' => 'pagado',
        'payment_method' => $validated['payment_method'],
        'billing_address' => $validated['billing_address'] ?? $order->billing_address,
        'shipping_address' => $validated['shipping_address'] ?? $order->shipping_address,
    ]);

    return redirect()->route('orders.show', $order->id)
        ->with('success', 'Pago confirmado correctamente y direcciones actualizadas.');
}

    public function invoice(Order $order)
    {
        return view('orders.invoice', compact('order'));
    }

    public function exportPdf(Order $order)
    {
        $pdf = Pdf::loadView('orders.invoice', compact('order'));
        return $pdf->download('factura_' . $order->order_number . '.pdf');
    }
   
}
