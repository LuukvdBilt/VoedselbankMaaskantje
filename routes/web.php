<?php

use App\Http\Controllers\InventoryController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\SupplierController;

Route::view('/', 'welcome')->name('home');

Route::get('/magazijn', [InventoryController::class, 'index']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::resource('inventory', InventoryController::class)->middleware('role:admin,manager');
});

//supplier routes
Route::get('/supplier', [SupplierController::class, 'index']
)->name('supplier.index');

Route::get('/supplier/create', [SupplierController::class, 'create']
)->name('supplier.create');

Route::post('/supplier', [SupplierController::class, 'store']
)->name('supplier.store');

Route::get('/supplier/{id}/edit', [SupplierController::class, 'edit']
)->name('supplier.edit');

Route::put('/supplier/{id}', [SupplierController::class, 'update']
)->name('supplier.update');

Route::delete('/supplier/{id}', [SupplierController::class, 'destroy']
)->name('supplier.destroy');


require __DIR__.'/settings.php';
