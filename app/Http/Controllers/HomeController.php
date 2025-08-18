<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;   // ✅ importa el modelo
use App\Models\Category;

// app/Http/Controllers/HomeController.php
class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('featured', true)
            ->where('active', true)
            ->limit(8)
            ->get();
        
        $categories = Category::where('active', true)->get();
        
        return view('home', compact('featuredProducts', 'categories'));
    }
}