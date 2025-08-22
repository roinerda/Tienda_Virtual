@extends('layouts.admin')

@section('content')
<div class="container mx-auto py-6">
    <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-gray-200">
        Panel de Administración
    </h2>
</div>

<div class="flex flex-wrap gap-3">


   <a href="{{ route('admin.productos.index') }}"
   style="color: black !important; background-color: #2563eb !important; padding: 0.5rem 1rem; border-radius: 0.375rem; display: inline-block;">
   Administrar Productos
</a>


<a href="{{ route('admin.productos.create') }}"
    style="color: black !important; background-color: #2563eb !important; padding: 0.5rem 1rem; border-radius: 0.375rem; display: inline-block;">
    Crear Producto
</a>

<a href="{{ route('admin.categorias.index') }}"
   style="color: black !important; background-color: #2563eb !important; padding: 0.5rem 1rem; border-radius: 0.375rem; display: inline-block;">
    Ver Categorías
</a>

<a href="{{ route('admin.categorias.create') }}"
    style="color: black !important; background-color: #2563eb !important; padding: 0.5rem 1rem; border-radius: 0.375rem; display: inline-block;">
    Crear Categoría
</a>

</div>
@endsection