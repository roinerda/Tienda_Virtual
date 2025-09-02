<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    // Muestra el listado de productos activos con filtros opcionales por categoría, búsqueda y rango de precios
    public function index(Request $request)
    {
        $query = Product::with('category')->where('active', true);

        // Filtrar por categoría usando el slug
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filtrar por término de búsqueda en el nombre del producto
        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        // Filtrar por precio mínimo
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        // Filtrar por precio máximo
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Paginación con persistencia de filtros en la URL
        $products = $query->paginate(12)->withQueryString();

        // Obtener categorías activas para los filtros
        $categories = Category::where('active', true)->get();

        // Renderizar vista con productos, categorías y filtros aplicados
        return view('dashboard', [
            'products'   => $products,
            'categories' => $categories,
            'filters'    => $request->only(['search', 'category', 'min_price', 'max_price'])
        ]);
    }

    // Muestra el detalle de un producto y obtiene productos relacionados de la misma categoría
    public function show(Product $product)
    {
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('producto.show', compact('product'));
    }
}
