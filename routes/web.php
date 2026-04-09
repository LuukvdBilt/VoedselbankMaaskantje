<?php

use App\Http\Controllers\InventoryController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::get('/magazijn', [InventoryController::class, 'index']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('allergies', 'allergies')->name('allergies');
    Route::resource('inventory', InventoryController::class)->middleware('role:admin,manager');
});



require __DIR__.'/settings.php';
