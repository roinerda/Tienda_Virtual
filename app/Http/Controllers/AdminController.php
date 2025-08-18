<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

// app/Http/Controllers/Admin/AdminController.php
class AdminController extends Controller
{
    public function __construct()
{
   
}
    
    public function dashboard()
    {
        $totalOrders = Order::count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        $totalProducts = Product::count();
        $totalUsers = User::where('is_admin', false)->count();
        
        return view('admin.dashboard', compact('totalOrders', 'totalRevenue', 'totalProducts', 'totalUsers'));
    }
    
    public function salesReport()
    {
        $salesData = Order::selectRaw('DATE(created_at) as date, COUNT(*) as orders, SUM(total_amount) as revenue')
            ->where('payment_status', 'paid')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();
            
        return view('admin.reports.sales', compact('salesData'));
    }
}
