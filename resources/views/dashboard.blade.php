@extends('layouts.app')

@section('title', 'Dashboard de Productos')

@section('content')
{{-- Filtros --}}
<form method="GET" action="{{ route('client.dashboard') }}" class="row g-3 mb-4">
    <div class="col-md-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar..." class="form-control">
    </div>
    <div class="col-md-3">
        <select name="category" class="form-select">
            <option value="">Todas las categorías</option>
            @if(!empty($categories))
                @foreach($categories as $cat)
                    <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Filtrar</button>
    </div>
</form>

{{-- Tabla --}}
<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Stock</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                @php
                    $images = is_array($product->images) ? $product->images : json_decode($product->images, true);
                    $imagePath = !empty($images) && is_array($images) ? $images[0] : null;
                @endphp
                <tr>
                    <td>
                        @if($imagePath && file_exists(public_path('storage/' . $imagePath)))
                            <img src="{{ asset('storage/' . $imagePath) }}" alt="Imagen" width="60">
                        @else
                            <span class="text-muted">Sin imagen</span>
                        @endif
                    </td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name ?? 'Sin categoría' }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ number_format($product->price, 2) }} colones</td>
                    <td>
                        {{-- Ver detalles --}}
                        <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-info">Ver detalles</a>

                        {{-- Agregar al carrito --}}
                        <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="btn btn-sm btn-success">Agregar al carrito</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No se encontraron productos</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Paginación --}}
<div class="d-flex justify-content-center">
    {{ $products->links() }}
</div>
@endsection