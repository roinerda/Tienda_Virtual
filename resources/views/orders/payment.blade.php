@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">💳 Proceso de Pago</h2>

    <div class="card mb-4">
        <div class="card-body">
            <h5>Pedido: {{ $order->order_number }}</h5>
            <p>Total a pagar: <strong>₡{{ number_format($order->total_amount, 2) }}</strong></p>
            <p>Estado actual: <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span></p>
        </div>
    </div>

    <form action="{{ route('orders.confirmPayment', $order->id) }}" method="POST">
        @csrf

        <h5 class="mt-4">📦 Dirección de envío</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Calle</label>
                <input type="text" name="shipping_address[calle]" class="form-control"
                    value="{{ $order->shipping_address['calle'] ?? '' }}">
            </div>
            <div class="col-md-6 mb-3">
                <label>Ciudad</label>
                <input type="text" name="shipping_address[ciudad]" class="form-control"
                    value="{{ $order->shipping_address['ciudad'] ?? '' }}">
            </div>
            <div class="col-md-6 mb-3">
                <label>Provincia</label>
                <input type="text" name="shipping_address[provincia]" class="form-control"
                    value="{{ $order->shipping_address['provincia'] ?? '' }}">
            </div>
            <div class="col-md-6 mb-3">
                <label>Código postal</label>
                <input type="text" name="shipping_address[codigo_postal]" class="form-control"
                    value="{{ $order->shipping_address['codigo_postal'] ?? '' }}">
            </div>
        </div>

        <h5 class="mt-4">🧾 Dirección de facturación</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Calle</label>
                <input type="text" name="billing_address[calle]" class="form-control"
                    value="{{ $order->billing_address['calle'] ?? '' }}">
            </div>
            <div class="col-md-6 mb-3">
                <label>Ciudad</label>
                <input type="text" name="billing_address[ciudad]" class="form-control"
                    value="{{ $order->billing_address['ciudad'] ?? '' }}">
            </div>
            <div class="col-md-6 mb-3">
                <label>Provincia</label>
                <input type="text" name="billing_address[provincia]" class="form-control"
                    value="{{ $order->billing_address['provincia'] ?? '' }}">
            </div>
            <div class="col-md-6 mb-3">
                <label>Código postal</label>
                <input type="text" name="billing_address[codigo_postal]" class="form-control"
                    value="{{ $order->billing_address['codigo_postal'] ?? '' }}">
            </div>
        </div>

        <div class="mb-4 mt-4">
            <label for="payment_method" class="form-label">Selecciona método de pago:</label>
            <select name="payment_method" id="payment_method" class="form-select">
                <option value="tarjeta" {{ $order->payment_method === 'tarjeta' ? 'selected' : '' }}>Tarjeta de crédito</option>
                <option value="paypal" {{ $order->payment_method === 'paypal' ? 'selected' : '' }}>PayPal</option>
                <option value="transferencia" {{ $order->payment_method === 'transferencia' ? 'selected' : '' }}>Transferencia bancaria</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Confirmar pago</button>
    </form>

   
</div>
@endsection