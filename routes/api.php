<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AppointmentController;
use App\Http\Controllers\Api\AvailabilityController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn (Request $request) => $request->user());
    
    Route::get('/available-slots', [AvailabilityController::class, 'getAvailableSlots']);
    Route::apiResource('appointments', AppointmentController::class);
});
/* Route::apiResource('appointments', AppointmentController::class);
 */

