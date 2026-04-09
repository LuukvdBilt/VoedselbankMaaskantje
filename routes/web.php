<?php

use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\CustomerRegistrationController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

// supplier routes
Route::get('/supplier', [SupplierController::class, 'index']
)->name('supplier.index');

Route::get('/supplier/{id}/edit', [SupplierController::class, 'edit']
)->name('supplier.edit');

Route::put('/supplier/{id}', [SupplierController::class, 'update']
)->name('supplier.update');

Route::delete('/supplier/{id}', [SupplierController::class, 'destroy']
)->name('supplier.destroy');

require __DIR__.'/settings.php';
// routes/web.php - Add this to auth middleware group
Route::middleware('auth')->prefix('customers')->group(function () {
    // Customer Registration
    Route::get('/registration', [CustomerRegistrationController::class, 'index'])->name('customersregistration.index');
    Route::post('/registration', [CustomerRegistrationController::class, 'store'])->name('customersregistration.store');
    Route::get('/{id}/registration', [CustomerRegistrationController::class, 'show'])->name('customersregistration.show');
    Route::get('/{id}/registration/edit', [CustomerRegistrationController::class, 'edit'])->name('customersregistration.edit');
    Route::put('/{id}/registration', [CustomerRegistrationController::class, 'update'])->name('customersregistration.update');

    // Customer Orders
    Route::get('/{clientId}/orders', [CustomerOrderController::class, 'index'])->name('customersorders.index');
    Route::get('/{clientId}/orders/create', [CustomerOrderController::class, 'create'])->name('customersorders.create');
    Route::post('/{clientId}/orders', [CustomerOrderController::class, 'store'])->name('customersorders.store');
    Route::get('/{clientId}/orders/{orderId}/edit', [CustomerOrderController::class, 'edit'])->name('customersorders.edit');
    Route::put('/{clientId}/orders/{orderId}', [CustomerOrderController::class, 'update'])->name('customersorders.update');
    Route::delete('/{clientId}/orders/{orderId}', [CustomerOrderController::class, 'destroy'])->name('customersorders.destroy');
});