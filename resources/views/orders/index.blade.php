@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">📦 Mis Pedidos</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($orders->isEmpty())
        <div class="alert alert-info">No tienes pedidos aún. ¡Explora productos y realiza tu primera compra!</div>
    @else
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th># Pedido</th>
                    <th>Estado</th>
                    <th>Pago</th>
                    <th>Total</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>
                            <span class="badge bg-{{ $order->status === 'cancelado' ? 'danger' : ($order->status === 'completado' ? 'success' : 'secondary') }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $order->payment_status === 'pagado' ? 'success' : 'warning' }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </td>
                        <td>₡{{ number_format($order->total_amount, 2) }}</td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="d-flex gap-2">
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary">Ver</a>
                            @if($order->status !== 'cancelado')
                                <form action="{{ route('orders.cancel', $order) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Cancelar</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection