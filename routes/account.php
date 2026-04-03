<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('account')->group(function () {
  Route::redirect('/', 'account/orders');

  Route::get('orders', [OrderController::class, 'index'])->name('account.orders');
  Route::get('addresses', [AddressController::class, 'index'])->name('account.addresses');
});