@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalle del Pedido</h1>

    <div class="mb-4">
        <strong>Número de pedido:</strong> {{ $order->order_number }}<br>
        <strong>Estado:</strong> {{ ucfirst($order->status) }}<br>
        <strong>Pago:</strong> {{ ucfirst($order->payment_status) }}<br>
        <strong>Total:</strong> ${{ number_format($order->total_amount, 2) }}<br>
        <strong>Fecha:</strong> {{ $order->created_at->format('d/m/Y H:i') }}
    </div>

    <h4>Ítems del pedido</h4>
    @if($order->orderItems->isEmpty())
        <p>No hay ítems en este pedido.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                    <tr>
                        <td>{{ $item->product_name ?? 'Producto' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->price, 2) }}</td>
                        <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ route('orders.index') }}" class="btn btn-secondary">Volver</a>
</div>
@endsection