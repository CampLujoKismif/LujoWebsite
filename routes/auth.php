<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    Volt::route('login', 'auth.login')
        ->name('login');

    Volt::route('register', 'auth.register')
        ->name('register');

    Volt::route('forgot-password', 'auth.forgot-password')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'auth.reset-password')
        ->name('password.reset');

});

// Email verification can be done without authentication
Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

// Resend verification email
Route::post('verify-email/resend', [VerifyEmailController::class, 'resend'])
    ->middleware('throttle:6,1')
    ->name('verification.resend');

Route::middleware('auth')->group(function () {
    Volt::route('verify-email', 'auth.verify-email')
        ->name('verification.notice');

    Volt::route('confirm-password', 'auth.confirm-password')
        ->name('password.confirm');
    
    Volt::route('force-change-password', 'auth.force-change-password')
        ->name('password.force-change');
});

Route::post('logout', App\Livewire\Actions\Logout::class)
    ->name('logout');
