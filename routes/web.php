<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchPublishedProductsController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::controller(ShopController::class)->group(function () {
    Route::get('/',  'index')->name('home');
    Route::get('products/{product}', 'show')->name('product.show');
});

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

    Route::get('search/products', SearchPublishedProductsController::class)
        ->name('search.products');

    Route::post('addresses', [AddressController::class, 'store'])
        ->name('addresses.store');

    Route::patch('addresses/{address}', [AddressController::class, 'update'])
        ->name('addresses.update');

    Route::patch('addresses/{address}/default', [AddressController::class, 'updateDefault'])
        ->name('addresses.default.update');

    Route::delete('addresses/{address}', [AddressController::class, 'destroy'])
        ->name('addresses.destroy');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/account.php';
require __DIR__ . '/stripe.php';
