<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\AdminMenuController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminTransactionController;
use App\Http\Controllers\AdminQrCodeController;
use Illuminate\Support\Facades\Route;

// Routes Menu (Public - Customer)
Route::get('/', [MenuController::class, 'index'])->name('home');
Route::get('/pesanan-saya', [MenuController::class, 'myOrders'])->name('orders.mine');
Route::get('/pesanan-saya/status', [MenuController::class, 'myOrdersStatus'])->name('orders.mine.status');
Route::post('/pesanan-saya/{order}/cancel', [MenuController::class, 'cancelMyOrder'])->name('orders.mine.cancel');
Route::get('/menu/{category}', [MenuController::class, 'getMenuByCategory'])->name('menu.category');

// Routes Cart (Public - Customer)
Route::post('/cart/add', [MenuController::class, 'addToCart'])->name('cart.add');
Route::get('/cart/view', [MenuController::class, 'viewCart'])->name('cart.view');
Route::post('/cart/{id}/update', [MenuController::class, 'updateCartQuantity'])->name('cart.update');
Route::delete('/cart/{id}/remove', [MenuController::class, 'removeFromCart'])->name('cart.remove');
Route::delete('/cart/clear', [MenuController::class, 'clearCart'])->name('cart.clear');
Route::post('/cart/order', [MenuController::class, 'order'])->name('cart.order');

// ═══════ ADMIN ROUTES ═══════
Route::middleware('auth')->prefix('admin')->group(function () {

    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Menu CRUD
    Route::get('/menu', [AdminMenuController::class, 'index'])->name('admin.menu.index');
    Route::get('/menu/create', [AdminMenuController::class, 'create'])->name('admin.menu.create');
    Route::post('/menu', [AdminMenuController::class, 'store'])->name('admin.menu.store');
    Route::get('/menu/{menu}/edit', [AdminMenuController::class, 'edit'])->name('admin.menu.edit');
    Route::put('/menu/{menu}', [AdminMenuController::class, 'update'])->name('admin.menu.update');
    Route::delete('/menu/{menu}', [AdminMenuController::class, 'destroy'])->name('admin.menu.destroy');
    Route::post('/menu/{menu}/toggle-active', [AdminMenuController::class, 'toggleActive'])->name('admin.menu.toggle-active');

    // Orders
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
    Route::delete('/orders/{order}', [AdminOrderController::class, 'destroy'])->name('admin.orders.destroy');

    // Transactions
    Route::get('/transactions', [AdminTransactionController::class, 'index'])->name('admin.transactions.index');

    // QR Code
    Route::get('/qrcode', [AdminQrCodeController::class, 'index'])->name('admin.qrcode');

    // Preview toko untuk admin tanpa kontrol customer
    Route::get('/lihat-toko', [MenuController::class, 'index'])->name('admin.store.preview');
});

Route::get('/dashboard', function () {
    return redirect()->away('/admin/dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
