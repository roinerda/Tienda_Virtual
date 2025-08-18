<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

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
        // Aquí iría la lógica para crear el pedido desde el carrito, etc.
        $order = Order::create([
            'user_id' => Auth::id(),
            'status' => 'pendiente',
            'total' => $request->input('total'),
            // otros campos necesarios...
        ]);

        return redirect()->route('orders.index')->with('success', 'Pedido creado correctamente.');
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
}