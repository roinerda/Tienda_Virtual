<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryAdminController extends Controller
{
  public function index()
{
    $categorias = Category::all();
    return view('admin.categorias.index', compact('categorias'));
}

public function create()
{
    return view('admin.categorias.create');
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
    ]);

    Category::create([
        'name' => $request->name,
        'slug' => Str::slug($request->name),
        'description' => $request->description,
    ]);

    return redirect()->route('admin.categorias.index')->with('success', 'Categoría creada correctamente.');
}


public function edit(Category $categoria)
{
    return view('admin.categorias.edit', compact('categoria'));
}

public function update(Request $request, Category $categoria)
{
    $request->validate(['name' => 'required|string|max:255']);
    $categoria->update($request->all());
    return redirect()->route('admin.categorias.index')->with('success', 'Categoría actualizada.');
}

public function destroy(Category $categoria)
{
    $categoria->delete();
    return redirect()->route('admin.categorias.index')->with('success', 'Categoría eliminada.');
}
}