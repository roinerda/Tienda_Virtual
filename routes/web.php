<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\ProductAdminController;
use App\Http\Controllers\Admin\CategoryAdminController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Ruta Principal - Home / Catálogo Público
|--------------------------------------------------------------------------
*/

Route::get('/', [ProductController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Dashboard (cliente autenticado)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [ProductController::class, 'index'])->name('client.dashboard');
});

/*
|--------------------------------------------------------------------------
| Rutas de Perfil
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
    // CARRITO
    Route::prefix('carrito')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/agregar', [CartController::class, 'add'])->name('add');
        Route::patch('/{cartItem}', [CartController::class, 'update'])->name('update');
        Route::delete('/{cartItem}', [CartController::class, 'remove'])->name('remove');
        Route::post('/limpiar', [CartController::class, 'clear'])->name('clear');
        Route::post('/finalizar', [CartController::class, 'finalize'])->name('finalize');
      
    });

    //PEDIDOS
    Route::prefix('pedidos')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::patch('/{order}/cancelar', [OrderController::class, 'cancel'])->name('cancel');
        Route::get('/{order}/pago', [OrderController::class, 'payment'])->name('payment');
        Route::post('/{order}/confirmar-pago', [OrderController::class, 'confirmPayment'])->name('confirmPayment');

        // Factura
        Route::get('/{order}/factura', [OrderController::class, 'invoice'])->name('invoice');
        Route::get('/{order}/factura/pdf', [OrderController::class, 'exportPdf'])->name('exportPdf');
    });




    // MI CUENTA
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
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', AdminMiddleware::class])
    ->group(function () {

        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

        Route::resource('productos', ProductAdminController::class)
            ->parameters(['productos' => 'producto']);
        Route::post('productos/{producto}/toggle-status', [ProductAdminController::class, 'toggleStatus'])
            ->name('productos.toggle-status');

        Route::resource('categorias', CategoryAdminController::class)
            ->parameters(['categorias' => 'categoria']);
        Route::post('categorias/{categoria}/toggle-status', [CategoryAdminController::class, 'toggleStatus'])
            ->name('categorias.toggle-status');

        Route::prefix('pedidos')->name('orders.')->group(function () {
            Route::get('/', [AdminController::class, 'orders'])->name('index');
            Route::get('/{order}', [AdminController::class, 'orderShow'])->name('show');
            Route::patch('/{order}/estado', [AdminController::class, 'updateOrderStatus'])->name('update-status');
            Route::patch('/{order}/pago', [AdminController::class, 'updatePaymentStatus'])->name('update-payment');
        });

        Route::prefix('usuarios')->name('users.')->group(function () {
            Route::get('/', [AdminController::class, 'users'])->name('index');
            Route::get('/{user}', [AdminController::class, 'userShow'])->name('show');
            Route::patch('/{user}/toggle-admin', [AdminController::class, 'toggleAdmin'])->name('toggle-admin');
            Route::delete('/{user}', [AdminController::class, 'deleteUser'])->name('destroy');
        });

        Route::prefix('reportes')->name('reports.')->group(function () {
            Route::get('/ventas', [AdminController::class, 'salesReport'])->name('sales');
            Route::get('/productos', [AdminController::class, 'productsReport'])->name('products');
            Route::get('/usuarios', [AdminController::class, 'usersReport'])->name('users');
            Route::get('/inventario', [AdminController::class, 'inventoryReport'])->name('inventory');
        });

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
require __DIR__ . '/auth.php';
