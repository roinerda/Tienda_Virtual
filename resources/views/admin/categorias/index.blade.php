@extends('layouts.admin')

@section('content')
<h2>Categorías</h2>

<a href="{{ route('admin.categorias.create') }}" class="btn btn-primary mb-3">Nueva categoría</a>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($categorias as $categoria)
        <tr>
            <td>{{ $categoria->name }}</td>
            <td>
                <a href="{{ route('admin.categorias.edit', $categoria) }}" class="btn btn-sm btn-warning">Editar</a>
                <form action="{{ route('admin.categorias.destroy', $categoria) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta categoría?')">Eliminar</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="2">No hay categorías registradas.</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection