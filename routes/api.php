<?php

use App\Http\Controllers\Api\ReceiveMayarWebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Webhooks (no auth, signature-verified)
Route::post('/webhooks/mayar', ReceiveMayarWebhookController::class)
    ->name('webhooks.mayar');
