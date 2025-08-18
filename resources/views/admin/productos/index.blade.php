@extends('layouts.admin')

@section('content')
<h1>Productos</h1>
<a href="{{ route('admin.productos.create') }}" class="btn btn-primary">Nuevo Producto</a>

<table class="table mt-3">
    <thead>
        <tr>
            <th>Nombre</th><th>Precio</th><th>Stock</th><th>Categoría</th><th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($productos as $producto)
        <tr>
            <td>{{ $producto->name }}</td>
            <td>${{ number_format($producto->price, 2) }}</td>
            <td>{{ $producto->stock }}</td>
            <td>{{ $producto->category->name ?? 'Sin categoría' }}</td>
            <td>
                <a href="{{ route('admin.productos.edit', $producto) }}" class="btn btn-sm btn-warning">Editar</a>
                <form action="{{ route('admin.productos.destroy', $producto) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar producto?')">Eliminar</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection