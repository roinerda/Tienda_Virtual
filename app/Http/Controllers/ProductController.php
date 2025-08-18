<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;   
use App\Models\Category;

// app/Http/Controllers/ProductController.php
class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('active', true);
        
        if ($request->category) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        
        if ($request->search) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }
        
        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        
        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }
        
        $products = $query->paginate(12);
        $categories = Category::where('active', true)->get();
        
        return view('products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();
            
        return view('products.show', compact('product', 'relatedProducts'));
    }
}