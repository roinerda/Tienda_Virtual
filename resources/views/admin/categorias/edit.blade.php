@extends('layouts.admin')

@section('content')
<h2>Editar categoría</h2>

<form action="{{ route('admin.categorias.update', $categoria) }}" method="POST">
    @csrf @method('PUT')

    <div class="mb-3">
        <label for="name" class="form-label">Nombre de la categoría</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $categoria->name) }}" required>
    </div>

    <button type="submit" class="btn btn-primary">Actualizar categoría</button>
    <a href="{{ route('admin.categorias.index') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection