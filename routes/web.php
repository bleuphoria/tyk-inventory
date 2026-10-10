<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // General Staff Access
    Route::get('/alerts', [App\Http\Controllers\AlertController::class, 'index'])->name('alerts.index');

    // ==========================================
    // ADMIN ONLY MODULES
    // ==========================================
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', App\Http\Controllers\UserController::class);
        Route::get('/analytics', [App\Http\Controllers\AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('/activities', [App\Http\Controllers\ActivityLogController::class, 'index'])->name('activities.index');
        Route::get('/settings', [App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
    });

    // ==========================================
    // INVENTORY MANAGEMENT MODULES (Admin & Inventory Manager)
    // ==========================================
    Route::middleware('role:admin,inventory_manager')->group(function () {
        Route::resource('categories', App\Http\Controllers\CategoryController::class);
        Route::resource('suppliers', App\Http\Controllers\SupplierController::class);

        Route::get('/stock/update', [App\Http\Controllers\StockController::class, 'create'])->name('stock.create');
        Route::post('/stock/update', [App\Http\Controllers\StockController::class, 'store'])->name('stock.store');
        Route::get('/transactions', [App\Http\Controllers\TransactionController::class, 'index'])->name('transactions.index');

        // Reports
        Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/stock-summary', [App\Http\Controllers\ReportController::class, 'stockSummary'])->name('reports.stock_summary');
        Route::get('/reports/transactions-summary', [App\Http\Controllers\ReportController::class, 'transactionsSummary'])->name('reports.transactions_summary');
        Route::get('/reports/orders-summary', [App\Http\Controllers\ReportController::class, 'ordersSummary'])->name('reports.orders_summary');

        // PDF Reports
        Route::get('/reports/stock-summary-pdf', [App\Http\Controllers\ReportController::class, 'stockSummaryPdf'])->name('reports.stock_summary_pdf');
        Route::get('/reports/transactions-pdf', [App\Http\Controllers\ReportController::class, 'transactionsPdf'])->name('reports.transactions_pdf');
        Route::get('/reports/orders-pdf', [App\Http\Controllers\ReportController::class, 'ordersPdf'])->name('reports.orders_pdf');
    });

    // Products: Lookup & CRUD
    Route::get('/products', [App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
    Route::middleware('role:admin,inventory_manager')->group(function () {
        Route::get('/products/create', [App\Http\Controllers\ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [App\Http\Controllers\ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [App\Http\Controllers\ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [App\Http\Controllers\ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [App\Http\Controllers\ProductController::class, 'destroy'])->name('products.destroy');
    });

    // ==========================================
    // POINT OF SALE & ORDERS (Admin & Cashier)
    // ==========================================
    Route::middleware('role:admin,cashier')->group(function () {
        Route::resource('orders', App\Http\Controllers\OrderController::class)->only(['index', 'create', 'store', 'show']);
        Route::get('/orders/{order}/pdf', [App\Http\Controllers\OrderController::class, 'downloadPdf'])->name('orders.pdf');
    });
});

require __DIR__.'/auth.php';
