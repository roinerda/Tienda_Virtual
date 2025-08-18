@extends('layouts.admin')

@section('content')
<h2>Crear nuevo producto</h2>

<form action="{{ route('admin.productos.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label for="name" class="form-label">Nombre</label>
        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
    </div>

    <div class="mb-3">
        <label for="price" class="form-label">Precio</label>
        <input type="number" name="price" class="form-control" step="0.01" value="{{ old('price') }}" required>
    </div>

    <div class="mb-3">
        <label for="stock" class="form-label">Stock</label>
        <input type="number" name="stock" class="form-control" value="{{ old('stock') }}" required>
    </div>

    <div class="mb-3">
        <label for="category_id" class="form-label">Categoría</label>
        <select name="category_id" class="form-select" required>
            <option value="">Seleccionar categoría</option>
            @foreach($categorias as $categoria)
                <option value="{{ $categoria->id }}">{{ $categoria->name }}</option>
            @endforeach
        </select>
    </div>
    
    <div class="mb-4">
    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descripción</label>
    <textarea name="description" id="description" rows="4"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
        placeholder="Descripción del producto"></textarea>
</div>

<div class="mb-4">
    <label for="short_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descripción corta</label>
    <textarea name="short_description" id="short_description" rows="2"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
        placeholder="Resumen breve del producto"></textarea>
</div>

    <div class="mb-3">
        <label for="images" class="form-label">Imagen</label>
        <input type="file" name="images" class="form-control">
    </div>

    <button type="submit" class="btn btn-success">Guardar producto</button>
    <a href="{{ route('admin.productos.store') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection