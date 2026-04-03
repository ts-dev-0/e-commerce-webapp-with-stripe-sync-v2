<?php

use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('account')->group(function () {
    Route::delete('settings/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('settings/password', [PasswordController::class, 'edit'])->name('user-password.edit');

    Route::put('settings/password', [PasswordController::class, 'update'])
        ->middleware('throttle:6,1')
        ->name('user-password.update');

    // TODO: 機能実装後に追加する
    // Route::get('settings/appearance', function () {
    //     return Inertia::render('settings/appearance');
    // })->name('appearance.edit');

    // Route::get('settings/two-factor', [TwoFactorAuthenticationController::class, 'show'])
    //     ->name('two-factor.show');
});
