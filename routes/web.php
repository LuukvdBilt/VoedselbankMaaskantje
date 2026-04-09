<?php

use App\Http\Controllers\InventoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\AllergiesController;

Route::view('/', 'welcome')->name('home');

Route::get('/magazijn', [InventoryController::class, 'index']);
Route::get('/allergies', [AllergiesController::class, 'index']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // ALLERGIES CRUD (BELANGRIJK!)
    Route::resource('allergies', AllergiesController::class);

    Route::resource('inventory', InventoryController::class)
        ->middleware('role:admin,manager');
});

//supplier routes
Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier.index');
Route::get('/supplier/{id}/edit', [SupplierController::class, 'edit'])->name('supplier.edit');
Route::put('/supplier/{id}', [SupplierController::class, 'update'])->name('supplier.update');
Route::delete('/supplier/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy');

require __DIR__.'/settings.php';
