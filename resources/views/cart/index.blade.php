@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">🛒 Tu carrito de compras</h2>

    @if($cartItems->count())
    <form action="{{ route('cart.update', 'bulk') }}" method="POST">
        @csrf
        @method('PATCH')

        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Producto</th>
                    <th>Imagen</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartItems as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>
                        <img src="{{ asset('storage/' . $item->product->image) }}" width="60" alt="Imagen del producto">
                    </td>
                    <td>₡{{ number_format($item->price, 2) }}</td>
                    <td>{{ $item->product->stock }}</td>
                    <td>
                        <input type="number" name="quantities[{{ $item->id }}]"
                            value="{{ $item->quantity }}"
                            min="1"
                            max="{{ $item->product->stock }}"
                            class="form-control"
                            style="width: 80px;">
                    </td>
                    <td>₡{{ number_format($item->price * $item->quantity, 2) }}</td>
                    <td>
                        <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                <button type="submit" class="btn btn-primary me-2">Actualizar cantidades</button>
                <form action="{{ route('cart.clear') }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-warning">Vaciar carrito</button>
                </form>
            </div>
            <form action="{{ route('cart.finalize') }}" method="POST">
                @csrf
                <input type="hidden" name="payment_method" value="tarjeta">
                <button type="submit" class="btn btn-success">
                    Finalizar compra
                </button>
            </form>
        </div>
    </form>

    <div class="card mt-4">
        <div class="card-body">
            <h4>Resumen de compra</h4>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between">
                    <span>Subtotal:</span>
                    <strong>₡{{ number_format($subtotal, 2) }}</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>IVA (16%):</span>
                    <strong>₡{{ number_format($tax, 2) }}</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Envío:</span>
                    <strong>₡{{ number_format($shipping, 2) }}</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Total:</span>
                    <strong>₡{{ number_format($total, 2) }}</strong>
                </li>
            </ul>
        </div>
    </div>
    @else
    <div class="alert alert-info">
        Tu carrito está vacío. ¡Agrega productos para comenzar tu compra!
    </div>
    @endif
</div>
@endsection