<?php

use App\Http\Controllers\Api\MidtransWebhookController;
use App\Http\Controllers\Api\ReceiveMayarWebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Webhooks (no auth, signature-verified)
Route::post('/webhooks/mayar', ReceiveMayarWebhookController::class)
    ->middleware('throttle:webhook')
    ->name('webhooks.mayar');

Route::post('/webhooks/midtrans', MidtransWebhookController::class)
    ->middleware('throttle:webhook')
    ->name('webhooks.midtrans');

