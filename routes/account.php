<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
  Route::get('account/orders', [OrderController::class, 'index'])->name('account.orders');
  Route::get('account/addresses', [AddressController::class, 'index'])->name('account.addresses');
});