<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('v1')->middleware(\App\Http\Middleware\AuthenticateDevice::class)->group(function () {
    require __DIR__ . '/api/v1/app.php';
});