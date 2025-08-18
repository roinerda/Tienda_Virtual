<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\ProductAdminController;
use App\Http\Controllers\Admin\CategoryAdminController;
use App\Http\Middleware\AdminMiddleware;  // ← AGREGAR ESTA LÍNEA
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Ruta Principal
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Dashboard (Laravel por defecto)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Rutas de Perfil (Laravel por defecto)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Rutas de la Tienda Virtual - PÚBLICAS
|--------------------------------------------------------------------------
*/

// Productos
Route::get('/productos', [ProductController::class, 'index'])->name('products.index');
Route::get('/producto/{product}', [ProductController::class, 'show'])->name('products.show');

// Categorías
Route::get('/categoria/{category}', [ProductController::class, 'byCategory'])->name('products.category');

// Búsqueda
Route::get('/buscar', [ProductController::class, 'search'])->name('products.search');

/*
|--------------------------------------------------------------------------
| Rutas que requieren autenticación
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    
    // CARRITO DE COMPRAS
    Route::prefix('carrito')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/agregar', [CartController::class, 'add'])->name('add');
        Route::patch('/{cartItem}', [CartController::class, 'update'])->name('update');
        Route::delete('/{cartItem}', [CartController::class, 'remove'])->name('remove');
        Route::post('/limpiar', [CartController::class, 'clear'])->name('clear');
    });
    
    
    // PEDIDOS
    Route::prefix('pedidos')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::patch('/{order}/cancelar', [OrderController::class, 'cancel'])->name('cancel');
    });
    
    
    // PROCESO DE PAGO
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        Route::post('/procesar', [PaymentController::class, 'process'])->name('process');
        Route::get('/exito/{order}', [PaymentController::class, 'success'])->name('success');
        Route::get('/error', [PaymentController::class, 'error'])->name('error');
    });
    
    // PERFIL EXTENDIDO (adicional al de Laravel)
    Route::prefix('mi-cuenta')->name('account.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/editar', [ProfileController::class, 'editExtended'])->name('edit');
        Route::put('/actualizar', [ProfileController::class, 'updateExtended'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
        Route::get('/direcciones', [ProfileController::class, 'addresses'])->name('addresses');
    });
});

/*
|--------------------------------------------------------------------------
| Rutas de Administración
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', AdminMiddleware::class])->group(function () {
    
    // Dashboard de admin
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Gestión de productos
    
Route::get('productos', [ProductAdminController::class, 'index'])->name('productos.index');
Route::get('productos/create', [ProductAdminController::class, 'create'])->name('productos.create');
Route::post('productos', [ProductAdminController::class, 'store'])->name('productos.store');
Route::get('productos/{product}', [ProductAdminController::class, 'show'])->name('productos.show');
Route::get('productos/{product}/edit', [ProductAdminController::class, 'edit'])->name('productos.edit');
Route::put('productos/{product}', [ProductAdminController::class, 'update'])->name('productos.update');
Route::delete('productos/{product}', [ProductAdminController::class, 'destroy'])->name('productos.destroy');
Route::post('productos/{product}/toggle-status', [ProductAdminController::class, 'toggleStatus'])->name('productos.toggle-status');

   
    
   // Gestión de categorías
Route::get('categorias', [CategoryAdminController::class, 'index'])->name('categorias.index');
Route::get('categorias/create', [CategoryAdminController::class, 'create'])->name('categorias.create');
Route::post('categorias', [CategoryAdminController::class, 'store'])->name('categorias.store');
Route::get('categorias/{category}', [CategoryAdminController::class, 'show'])->name('categorias.show');
Route::get('categorias/{category}/edit', [CategoryAdminController::class, 'edit'])->name('categorias.edit');
Route::put('categorias/{category}', [CategoryAdminController::class, 'update'])->name('categorias.update');
Route::delete('categorias/{category}', [CategoryAdminController::class, 'destroy'])->name('categorias.destroy');
Route::post('categorias/{category}/toggle-status', [CategoryAdminController::class, 'toggleStatus'])->name('categorias.toggle-status');
    
    // Gestión de pedidos
    Route::prefix('pedidos')->name('orders.')->group(function () {
        Route::get('/', [AdminController::class, 'orders'])->name('index');
        Route::get('/{order}', [AdminController::class, 'orderShow'])->name('show');
        Route::patch('/{order}/estado', [AdminController::class, 'updateOrderStatus'])->name('update-status');
        Route::patch('/{order}/pago', [AdminController::class, 'updatePaymentStatus'])->name('update-payment');
    });
    
    // Gestión de usuarios
    Route::prefix('usuarios')->name('users.')->group(function () {
        Route::get('/', [AdminController::class, 'users'])->name('index');
        Route::get('/{user}', [AdminController::class, 'userShow'])->name('show');
        Route::patch('/{user}/toggle-admin', [AdminController::class, 'toggleAdmin'])->name('toggle-admin');
        Route::delete('/{user}', [AdminController::class, 'deleteUser'])->name('destroy');
    });
    
    
    // Reportes
    Route::prefix('reportes')->name('reports.')->group(function () {
        Route::get('/ventas', [AdminController::class, 'salesReport'])->name('sales');
        Route::get('/productos', [AdminController::class, 'productsReport'])->name('products');
        Route::get('/usuarios', [AdminController::class, 'usersReport'])->name('users');
        Route::get('/inventario', [AdminController::class, 'inventoryReport'])->name('inventory');
    });
    
    
    // Configuración
    Route::prefix('configuracion')->name('settings.')->group(function () {
        Route::get('/', [AdminController::class, 'settings'])->name('index');
        Route::post('/actualizar', [AdminController::class, 'updateSettings'])->name('update');
    });
});

/*
|--------------------------------------------------------------------------
| Rutas de autenticación
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';