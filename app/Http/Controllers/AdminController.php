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
    // Constructor del controlador (puede usarse para aplicar middlewares en el futuro)
    public function __construct()
    {
        // Aquí podrías aplicar middleware como auth o admin si lo necesitas
    }

    // Muestra el panel de administración con métricas generales del sistema
    public function dashboard()
    {
        $totalOrders = Order::count(); // Total de pedidos realizados
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount'); // Ingresos confirmados
        $totalProducts = Product::count(); // Total de productos registrados
        $totalUsers = User::where('is_admin', false)->count(); // Total de usuarios no administradores

        return view('admin.dashboard', compact('totalOrders', 'totalRevenue', 'totalProducts', 'totalUsers'));
    }

    // Genera un reporte de ventas agrupado por fecha, incluyendo número de pedidos e ingresos
    public function salesReport()
    {
        $salesData = Order::selectRaw('DATE(created_at) as date, COUNT(*) as orders, SUM(total_amount) as revenue')
            ->where('payment_status', 'paid') // Solo pedidos pagados
            ->groupBy('date') // Agrupar por fecha
            ->orderBy('date', 'desc') // Ordenar de más reciente a más antiguo
            ->get();

        return view('admin.reports.sales', compact('salesData'));
    }
}
