<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchPublishedProductController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('products/{product}', [ProductController::class, 'show']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('cart', CartController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    Route::get('favorites', [FavoriteController::class, 'index'])
    ->name('favorites.index');

    Route::post('favorites', [FavoriteController::class, 'store'])
        ->name('favorites.store');

    Route::delete('favorites', [FavoriteController::class, 'destroy'])
        ->name('favorites.destroy');

    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    Route::resource('checkout', CheckoutController::class)
        ->only(['index', 'store']);

    Route::resource('reviews', ReviewController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    Route::get('search/products', SearchPublishedProductController::class)->name('search.products');

    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

require __DIR__.'/settings.php';
