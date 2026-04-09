<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\SupplierController;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

//supplier routes
Route::get('/supplier', [SupplierController::class, 'index']
)->name('supplier.index');

Route::get('/supplier/{id}/edit', [SupplierController::class, 'edit']
)->name('supplier.edit');

Route::put('/supplier/{id}', [SupplierController::class, 'update']
)->name('supplier.update');

Route::delete('/supplier/{id}', [SupplierController::class, 'destroy']
)->name('supplier.destroy');


require __DIR__.'/settings.php';
