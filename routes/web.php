<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentSettingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $user = Auth::user();

    if (! $user) {
        return redirect()->route('login');
    }

    return redirect()->route($user->isAdmin() ? 'admin.dashboard' : 'kasir.index');
})->name('home');

// Menu publik untuk pelanggan yang memesan lewat HP (tanpa login).
Route::get('/pesan', [CustomerOrderController::class, 'menu'])->name('pelanggan.menu');
Route::post('/pesan', [CustomerOrderController::class, 'store'])->middleware('throttle:10,1')->name('pelanggan.order.store');
Route::get('/pesan/{order}/status', [CustomerOrderController::class, 'status'])->middleware('signed')->name('pelanggan.order.status');

// Lacak pesanan pelanggan lewat barcode / kode lacak.
Route::get('/lacak', [CustomerOrderController::class, 'trackForm'])->name('pelanggan.track.form');
Route::get('/lacak/{code}', [CustomerOrderController::class, 'track'])->name('pelanggan.track');
Route::get('/api/lacak/{code}', [CustomerOrderController::class, 'trackStatus'])->name('pelanggan.track.status');

// Autentikasi staf (admin & kasir).
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Kasir: input pesanan, cetak struk, dan isi nama penjaga kasir.
Route::middleware(['auth', 'role:kasir,admin'])->group(function () {
    Route::get('/kasir', [OrderController::class, 'cashier'])->name('kasir.index');
    Route::post('/kasir/order', [OrderController::class, 'store'])->name('kasir.order.store');
    Route::get('/kasir/pembayaran', [OrderController::class, 'payments'])->name('kasir.payments');
    Route::get('/kasir/notifikasi', [OrderController::class, 'notifications'])->name('kasir.notifications');
    Route::post('/kasir/pembayaran/{order}/konfirmasi', [OrderController::class, 'confirmPayment'])->name('kasir.payment.confirm');
    Route::get('/kasir/order/{order}/struk', [OrderController::class, 'printReceipt'])->name('kasir.order.receipt');

    Route::get('/kasir/staf', [StaffController::class, 'cashierIndex'])->name('kasir.staff');
    Route::post('/kasir/staf', [StaffController::class, 'cashierStore'])->name('kasir.staff.store');
    Route::delete('/kasir/staf/{schedule}', [StaffController::class, 'cashierDestroy'])->name('kasir.staff.destroy');
});

// Khusus admin: staf, menu, laporan keuangan, dan dashboard dapur.
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard admin: hanya melihat staf yang bertugas hari ini dan berikutnya.
    Route::get('/admin', [StaffController::class, 'index'])->name('admin.dashboard');

    // Kelola menu, harga, foto, dan stok.
    Route::get('/admin/menu', [ProductController::class, 'index'])->name('admin.products.index');
    Route::post('/admin/menu', [ProductController::class, 'store'])->name('admin.products.store');
    Route::get('/admin/menu/{product}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/admin/menu/{product}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/admin/menu/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');

    // Laporan pemasukan & pengeluaran real time + grafik.
    Route::get('/admin/laporan', [FinanceController::class, 'index'])->name('admin.reports');
    Route::post('/admin/transaksi', [FinanceController::class, 'store'])->name('admin.transactions.store');
    Route::delete('/admin/transaksi/{transaction}', [FinanceController::class, 'destroy'])->name('admin.transactions.destroy');

    // Kelola QRIS dan rekening untuk pembayaran pelanggan.
    Route::get('/admin/pembayaran', [PaymentSettingController::class, 'index'])->name('admin.payment-settings');
    Route::post('/admin/pembayaran', [PaymentSettingController::class, 'update'])->name('admin.payment-settings.update');

    Route::get('/kitchen', [OrderController::class, 'kitchen'])->name('kitchen');
    Route::get('/api/orders/active', [OrderController::class, 'getActiveOrders'])->name('orders.active');
    Route::post('/api/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
});
