@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">📄 Detalle del Pedido</h1>

    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Número de pedido:</strong> {{ $order->order_number }}</p>
            <p><strong>Estado:</strong>
                <span class="badge bg-{{ $order->status === 'cancelado' ? 'danger' : ($order->status === 'completado' ? 'success' : 'secondary') }}">
                    {{ ucfirst($order->status) }}
                </span>
            </p>
            <p><strong>Pago:</strong>
                <span class="badge bg-{{ $order->payment_status === 'pagado' ? 'success' : 'warning' }}">
                    {{ ucfirst($order->payment_status) }}
                </span>
            </p>
            <p><strong>Método de pago:</strong> {{ ucfirst($order->payment_method ?? 'No especificado') }}</p>
            <p><strong>Fecha:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <h4>🛍️ Ítems del pedido</h4>
    @if($order->orderItems->isEmpty())
    <div class="alert alert-info">No hay ítems en este pedido.</div>
    @else
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
            <tr>
                <td>{{ $item->product_name ?? 'Producto' }}</td>
                <td>{{ $item->quantity }}</td>
                <td>₡{{ number_format($item->product_price, 2) }}</td>
                <td>₡{{ number_format($item->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="card mt-4">
        <div class="card-body">
            <h4>Resumen de compra</h4>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between">
                    <span>Subtotal:</span>
                    <strong>₡{{ number_format($order->subtotal, 2) }}</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>IVA (16%):</span>
                    <strong>₡{{ number_format($order->tax_amount, 2) }}</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Envío:</span>
                    <strong>₡{{ number_format($order->shipping_amount, 2) }}</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Total:</span>
                    <strong>₡{{ number_format($order->total_amount, 2) }}</strong>
                </li>
            </ul>
        </div>
    </div>

    <a href="{{ route('orders.index') }}" class="btn btn-secondary mt-4">← Volver a pedidos</a>
    <a href="{{ route('orders.payment', $order->id) }}" class="btn btn-sm btn-outline-success">
        Proceder al pago
    </a>
    <div class="mt-4 text-center">
        <a href="{{ route('orders.invoice', $order->id) }}" class="btn btn-outline-secondary">
            🧾 Ver factura
        </a>
    </div>
</div>
@endsection