<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchProductsController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::controller(ShopController::class)->group(function () {
    Route::get('/',  'index')->name('home');
    Route::get('products/{product}', 'show')->name('product.show');
});

Route::get('search/products', SearchProductsController::class)
    ->name('search.products');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('cart', CartController::class)
        ->name('cart.index');

    Route::resource('cart/items', CartItemController::class)
        ->only(['store', 'update', 'destroy'])
        ->names('cart.items');

    Route::get('orders', [OrderController::class, 'index'])
        ->name('orders.index');

    Route::resource('checkout', CheckoutController::class)
        ->only(['index', 'store']);

    Route::get('checkout/success', [CheckoutController::class, 'success'])
        ->name('checkout.success');

    Route::get('checkout/failed', [CheckoutController::class, 'failed'])
        ->name('checkout.failed');

    Route::resource('reviews', ReviewController::class)
        ->only(['store', 'update', 'destroy']);

    Route::resource('addresses', AddressController::class)
        ->only(['store', 'update', 'destroy']);

    Route::patch('addresses/{address}/default', [AddressController::class, 'updateDefault'])
        ->name('addresses.default.update');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/account.php';
require __DIR__ . '/stripe.php';
