@extends('layouts.admin')

@section('content')
<div class="container mx-auto py-6">
    <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-gray-200">Panel de Administración</h2>

    <div class="space-x-3">
       <a href="{{ route('admin.productos.index') }}"
   class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
    Administrar Productos
</a>

<a href="{{ route('admin.productos.create') }}"
   class="inline-block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
    Crear Producto
</a>

<a href="{{ route('admin.categorias.index') }}"
   class="inline-block px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
    Ver Categorías
</a>

<a href="{{ route('admin.categorias.create') }}"
   class="inline-block px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">
    Crear Categoría
</a>

    </div>
</div>
@endsection