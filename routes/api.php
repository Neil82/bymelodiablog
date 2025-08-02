<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TrackingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Analytics tracking API routes (no CSRF protection in API routes)
Route::prefix('tracking')->group(function () {
    Route::post('session/start', [TrackingController::class, 'startSession']);
    Route::post('events', [TrackingController::class, 'trackEvents']);
    Route::post('session/end', [TrackingController::class, 'endSession']);
});