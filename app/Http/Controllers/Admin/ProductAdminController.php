<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductAdminController extends Controller
{
    // Muestra todos los productos en el panel de administración
    public function index()
    {
        $productos = Product::all(); // Recupera todos los productos
        return view('admin.productos.index', compact('productos'));
    }

    // Muestra el formulario para crear un nuevo producto
    public function create()
    {
        $categorias = Category::all(); // Se cargan las categorías disponibles
        return view('admin.productos.create', compact('categorias'));
    }

    // Procesa y guarda un nuevo producto en la base de datos
    public function store(Request $request)
    {
        // Validación de los campos del formulario
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:255',
            'image' => 'required|image|max:2048',
        ]);

        //  Guarda la imagen en el disco 'public' dentro de la carpeta 'products'
        $path = $request->file('image')->store('products', 'public');

        //  Genera un SKU único para el producto
        $sku = 'PRD-' . strtoupper(Str::random(6));

        //  Genera un slug único basado en el nombre
        $baseSlug = Str::slug($request->name);
        $slug = $baseSlug;
        $counter = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        // ✅ Crea el producto en la base de datos
        Product::create([
            'name' => $request->name,
            'slug' => $slug,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'short_description' => $request->short_description,
            'images' => json_encode([$path]), // Se guarda como array JSON
            'sku' => $sku,
        ]);

        return redirect()->route('admin.productos.index')->with('success', 'Producto creado correctamente');
    }

    // Muestra el formulario para editar un producto existente
    public function edit(Product $producto)
    {
        $categorias = Category::all(); // Se cargan las categorías para el selector
        return view('admin.productos.edit', compact('producto', 'categorias'));
    }

    // Actualiza los datos de un producto existente
    public function update(Request $request, Product $producto)
    {
        // Validación de los campos actualizados
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        // ✅ Actualiza los campos excepto la imagen
        $producto->fill($request->except('image'));

        // ✅ Si se sube una nueva imagen, se actualiza
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $producto->images = json_encode([$path]);
        }

        $producto->save(); // Guarda los cambios

        return redirect()->route('admin.productos.index')->with('success', 'Producto actualizado correctamente');
    }

    // Elimina un producto de la base de datos
    public function destroy(Product $producto)
    {
        $producto->delete(); // Elimina el producto
        return redirect()->route('admin.productos.index')->with('success', 'Producto eliminado correctamente');
    }
}
