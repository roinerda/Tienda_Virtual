@extends('layouts.admin')

@section('content')
<h2>Crear nueva categoría</h2>

<form action="{{ route('admin.categorias.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label for="name" class="form-label">Nombre de la categoría</label>
        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
       
        <textarea name="description" rows="3"
    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
    placeholder="Descripción de la categoría"></textarea>
    </div>

    <button type="submit" class="btn btn-success">Guardar categoría</button>
    <a href="{{ route('admin.categorias.index') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection