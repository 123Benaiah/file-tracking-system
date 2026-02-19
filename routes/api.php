<?php

use App\Http\Controllers\ChatbotController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by the application within the "api" middleware
| group. They are prefixed with /api automatically.
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/chatbot/message', [ChatbotController::class, 'handleMessage']);
});

Route::middleware('auth:sanctum')->get('/chatbot/system-data', [ChatbotController::class, 'getSystemData']);
