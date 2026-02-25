<?php

use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products/{product}', [ProductController::class, 'show']);

Route::resource('favorites', FavoriteController::class)
    ->only(['index', 'store', 'destroy']);

Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::delete('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

require __DIR__.'/settings.php';
