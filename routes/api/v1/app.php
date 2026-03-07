<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('device')->group(function () {
    Route::get('heartbeat', [\App\Http\Controllers\Api\V1\DeviceController::class, 'heartbeat'])->name('api.v1.device.heartbeat');
});


Route::prefix('trips')->group(function () {
    Route::post('store', [\App\Http\Controllers\Api\V1\TripController::class, 'store'])->name('api.v1.trips.store');
});