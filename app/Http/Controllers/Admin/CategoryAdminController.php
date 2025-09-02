<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryAdminController extends Controller
{
    // Muestra todas las categorías en el panel de administración
    public function index()
    {
        $categorias = Category::all(); // Recupera todas las categorías
        return view('admin.categorias.index', compact('categorias'));
    }

    // Muestra el formulario para crear una nueva categoría
    public function create()
    {
        return view('admin.categorias.create');
    }

    // Procesa y guarda una nueva categoría en la base de datos
    public function store(Request $request)
    {
        // Validación de campos obligatorios
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Crea la categoría con slug generado automáticamente
        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        // Redirige con mensaje de éxito
        return redirect()->route('admin.categorias.index')->with('success', 'Categoría creada correctamente.');
    }

    // Muestra el formulario para editar una categoría existente
    public function edit(Category $categoria)
    {
        return view('admin.categorias.edit', compact('categoria'));
    }

    // Actualiza los datos de una categoría existente
    public function update(Request $request, Category $categoria)
    {
        $request->validate(['name' => 'required|string|max:255']); // Validación básica
        $categoria->update($request->all()); // Actualiza con los datos del formulario

        return redirect()->route('admin.categorias.index')->with('success', 'Categoría actualizada.');
    }

    // Elimina una categoría de la base de datos
    public function destroy(Category $categoria)
    {
        $categoria->delete(); // Elimina la categoría
        return redirect()->route('admin.categorias.index')->with('success', 'Categoría eliminada.');
    }
}
