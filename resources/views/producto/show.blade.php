@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container">
    <div class="row">
        {{-- Imagen principal --}}
        <div class="col-md-5">
            @php
                $images = is_array($product->images) ? $product->images : json_decode($product->images, true);
                $imagePath = !empty($images) ? $images[0] : null;
            @endphp

            @if($imagePath && file_exists(public_path('storage/' . $imagePath)))
                <img src="{{ asset('storage/' . $imagePath) }}" alt="Imagen del producto" class="img-fluid rounded shadow-sm">
            @else
                <div class="text-muted">Sin imagen disponible</div>
            @endif
        </div>

        {{-- Detalles del producto --}}
        <div class="col-md-7">
            <h2>{{ $product->name }}</h2>
            <p class="text-muted">{{ $product->short_description }}</p>

            <p><strong>Descripción:</strong></p>
            <p>{{ $product->description }}</p>

            <p><strong>SKU:</strong> {{ $product->sku }}</p>
            <p><strong>Categoría:</strong> {{ $product->category->name ?? 'Sin categoría' }}</p>
            <p><strong>Stock disponible:</strong> {{ $product->stock }}</p>

            @if($product->discount_price)
                <p>
                    <strong>Precio:</strong>
                    <span class="text-decoration-line-through text-danger">₡{{ number_format($product->price, 2) }}</span>
                    <span class="text-success ms-2">₡{{ number_format($product->discount_price, 2) }}</span>
                </p>
            @else
                <p><strong>Precio:</strong> ₡{{ number_format($product->price, 2) }}</p>
            @endif

            {{-- Botón de agregar al carrito --}}
            <form action="{{ route('cart.add') }}" method="POST" class="mt-3">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button type="submit" class="btn btn-success">Agregar al carrito</button>
            </form>
        </div>
    </div>

    {{-- Productos relacionados --}}
    @if(!empty($relatedProducts) && $relatedProducts->count())
        <hr>
        <h4>También te puede interesar</h4>
        <div class="row">
            @foreach($relatedProducts as $related)
                @php
                    $relatedImages = is_array($related->images) ? $related->images : json_decode($related->images, true);
                    $relatedImage = !empty($relatedImages) ? $relatedImages[0] : null;
                @endphp
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        @if($relatedImage && file_exists(public_path('storage/' . $relatedImage)))
                            <img src="{{ asset('storage/' . $relatedImage) }}" class="card-img-top" alt="{{ $related->name }}">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $related->name }}</h5>
                            <p class="card-text">₡{{ number_format($related->price, 2) }}</p>
                            <a href="{{ route('products.show', $related->slug) }}" class="btn btn-outline-primary btn-sm">Ver producto</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection