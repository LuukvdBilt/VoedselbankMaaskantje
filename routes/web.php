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


require __DIR__.'/settings.php';
