<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductAdminController extends Controller
{
    public function index()
    {
        $productos = Product::all();
        return view('admin.productos.index', compact('productos'));
    }

    public function create()
    {
        $categorias = Category::all();
        return view('admin.productos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:255',
            'image' => 'required|image|max:2048',
        ]);

        // ✅ Guardar imagen
        $path = $request->file('image')->store('products', 'public');

        // ✅ Generar SKU
        $sku = 'PRD-' . strtoupper(Str::random(6));

        // ✅ Generar slug único
        $baseSlug = Str::slug($request->name);
        $slug = $baseSlug;
        $counter = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        // ✅ Crear producto
        Product::create([
            'name' => $request->name,
            'slug' => $slug,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'short_description' => $request->short_description,
            'images' => json_encode([$path]),
            'sku' => $sku,
        ]);

        return redirect()->route('admin.productos.index')->with('success', 'Producto creado correctamente');
    }

    public function edit(Product $producto)
    {
        $categorias = Category::all();
        return view('admin.productos.edit', compact('producto', 'categorias'));
    }

    public function update(Request $request, Product $producto)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        // ✅ Actualizar campos
        $producto->fill($request->except('image'));

        // ✅ Actualizar imagen si se sube una nueva
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $producto->images = json_encode([$path]);
        }

        $producto->save();

        return redirect()->route('admin.productos.index')->with('success', 'Producto actualizado correctamente');
    }

    public function destroy(Product $producto)
    {
        $producto->delete();
        return redirect()->route('admin.productos.index')->with('success', 'Producto eliminado correctamente');
    }
}