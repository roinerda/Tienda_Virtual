@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-center">🧾 Factura de Compra</h2>

    <div class="card mb-4">
        <div class="card-body">
            <h5>Factura #: {{ $order->order_number }}</h5>
            <p><strong>Usuario:</strong> {{ $order->user->name }} (ID: {{ $order->user->id }})</p>
            <p><strong>Correo:</strong> {{ $order->user->email }}</p>
            <p><strong>Fecha de compra:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <h5>📦 Dirección de envío:</h5>
            <ul class="list-unstyled">
                <li>{{ $order->shipping_address['calle'] ?? '—' }}</li>
                <li>{{ $order->shipping_address['ciudad'] ?? '—' }}</li>
                <li>{{ $order->shipping_address['provincia'] ?? '—' }}</li>
                <li>{{ $order->shipping_address['codigo_postal'] ?? '—' }}</li>
            </ul>
        </div>
        <div class="col-md-6">
            <h5>🧾 Dirección de facturación:</h5>
            <ul class="list-unstyled">
                <li>{{ $order->billing_address['calle'] ?? '—' }}</li>
                <li>{{ $order->billing_address['ciudad'] ?? '—' }}</li>
                <li>{{ $order->billing_address['provincia'] ?? '—' }}</li>
                <li>{{ $order->billing_address['codigo_postal'] ?? '—' }}</li>
            </ul>
        </div>
    </div>

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
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>₡{{ number_format($item->product_price, 2) }}</td>
                    <td>₡{{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="table mt-4">
        <tr>
            <th>Subtotal:</th>
            <td>₡{{ number_format($order->subtotal, 2) }}</td>
        </tr>
        <tr>
            <th>IVA (16%):</th>
            <td>₡{{ number_format($order->tax_amount, 2) }}</td>
        </tr>
        <tr>
            <th>Envío:</th>
            <td>₡{{ number_format($order->shipping_amount, 2) }}</td>
        </tr>
        <tr>
            <th>Total:</th>
            <td><strong>₡{{ number_format($order->total_amount, 2) }}</strong></td>
        </tr>
        <tr>
            <th>Método de pago:</th>
            <td>{{ ucfirst($order->payment_method) }}</td>
        </tr>
    </table>

    <div class="mt-4 text-center">
        <a href="{{ route('orders.exportPdf', $order->id) }}" class="btn btn-danger">
            📄 Exportar a PDF
        </a>
    </div>
</div>
@endsection