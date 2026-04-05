<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Settings\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('account')->group(function () {
  Route::redirect('/', '/account/profile');

  Route::get('orders', [OrderController::class, 'index'])->name('account.orders');
  Route::get('addresses', [AddressController::class, 'index'])->name('account.addresses');

  Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('settings/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});