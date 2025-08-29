@extends('layouts.admin')

@section('content')
<h2>Editar producto</h2>

<form action="{{ route('admin.productos.update', $producto) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div class="mb-3">
        <label for="name" class="form-label">Nombre</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $producto->name) }}" required>
    </div>

    <div class="mb-3">
        <label for="price" class="form-label">Precio</label>
        <input type="number" name="price" class="form-control" step="0.01" value="{{ old('price', $producto->price) }}" required>
    </div>

    <div class="mb-3">
        <label for="stock" class="form-label">Stock</label>
        <input type="number" name="stock" class="form-control" value="{{ old('stock', $producto->stock) }}" required>
    </div>

    <div class="mb-3">
        <label for="category_id" class="form-label">Categoría</label>
        <select name="category_id" class="form-select" required>
            @foreach($categorias as $categoria)
                <option value="{{ $categoria->id }}" {{ $producto->category_id == $categoria->id ? 'selected' : '' }}>
                    {{ $categoria->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="image" class="form-label">Imagen actual</label><br>
        @if($producto->images)
          @foreach($producto->images as $img)
                <img src="{{ asset('storage/' . $img) }}" alt="Imagen" width="150" class="me-2 mb-2">
            @endforeach
        @else
            <p>No hay imágenes</p>
        @endif
        <input type="file" name="image" class="form-control mt-2">
    </div>

    <button type="submit" class="btn btn-primary">Actualizar producto</button>
    <a href="{{ route('admin.productos.index') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection