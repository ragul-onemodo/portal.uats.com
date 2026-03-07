<?php

use App\Http\Controllers\TripController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    // return view('welcome');

    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return redirect()->route('dashboard.index');
})->middleware(['auth', 'verified'])->name('dashboard');



require __DIR__ . '/auth.php';
require __DIR__ . '/app.php';