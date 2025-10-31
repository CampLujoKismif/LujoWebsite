<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RentalController;
use App\Http\Controllers\WebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Stripe Webhooks (no CSRF protection needed)
Route::post('/webhooks/stripe', [WebhookController::class, 'handleStripeWebhook'])->name('webhooks.stripe');

// Rental API routes
Route::prefix('rental')->group(function () {
    Route::get('/availability/{year}/{month}', [RentalController::class, 'getAvailability']);
    Route::post('/validate-discount', [RentalController::class, 'validateDiscountCode']);
    Route::post('/calculate-pricing', [RentalController::class, 'calculatePricing']);
    Route::post('/reservation', [RentalController::class, 'createReservation']);
    Route::get('/pricing', [RentalController::class, 'getPricing']);
    Route::get('/blackout-dates', [RentalController::class, 'getBlackoutDates']);
    Route::post('/create-payment-intent', [RentalController::class, 'createPaymentIntent']);
    Route::post('/confirm-payment', [RentalController::class, 'confirmPayment']);
    
});
