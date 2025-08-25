@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">🛒 Tu carrito de compras</h2>

    @if($cartItems->count())
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
            @php
            $images = is_array($item->product->images) ? $item->product->images : json_decode($item->product->images, true);
            $imagePath = !empty($images) && is_array($images) ? $images[0] : null;
            @endphp
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>
                    @if($imagePath && file_exists(public_path('storage/' . $imagePath)))
                    <img src="{{ asset('storage/' . $imagePath) }}" width="60" alt="Imagen del producto">
                    @else
                    <span class="text-muted">Sin imagen</span>
                    @endif
                </td>
                <td>₡{{ number_format($item->price, 2) }}</td>
                <td>{{ $item->product->stock }}</td>
                <td>
                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex">
                        @csrf
                        @method('PATCH')
                        <input type="number" name="quantity"
                            value="{{ $item->quantity }}"
                            min="1"
                            max="{{ $item->product->stock }}"
                            class="form-control me-2"
                            style="width: 80px;">
                        <button type="submit" class="btn btn-primary btn-sm">Actualizar</button>
                    </form>
                </td>
                <td>₡{{ number_format($item->price * $item->quantity, 2) }}</td>
                <td>
                    <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Acciones globales -->
    <div class="d-flex justify-content-between align-items-center mt-3">
        <form action="{{ route('cart.clear') }}" method="POST">
            @csrf
            <button class="btn btn-warning">Vaciar carrito</button>
        </form>

        <form action="{{ route('cart.finalize') }}" method="POST">
            @csrf
            <input type="hidden" name="payment_method" value="tarjeta">
            <button type="submit" class="btn btn-success">
                Finalizar compra
            </button>
        </form>
    </div>

    <!-- Resumen de compra -->
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