<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\EntityApplicationController;
use App\Http\Controllers\EntityController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\UserController;

Route::middleware(['auth'])
    ->prefix('app')
    ->group(function () {

        /*
        |------------------------------------------------------------------
        | Dashboard
        |------------------------------------------------------------------
        */

        Route::get('/', function () {
            return redirect(route('dashboard.index'));
        });

        Route::prefix('dashboard')->group(function () {
            Route::get(
                '/',
                [\App\Http\Controllers\DashboardController::class, 'index']
            )->middleware(['auth', 'verified'])->name('dashboard.index');

            Route::get('/dashboard-stats', [\App\Http\Controllers\DashboardController::class, 'stats'])->name('dashboard.stats');

        });

        /*
        |------------------------------------------------------------------
        | DataTable Routes (AJAX only)
        |------------------------------------------------------------------
        */
        Route::prefix('datatable')
            ->group(function () {

                Route::post('entities', [EntityController::class, 'datatable'])
                    ->name('entities.dt');

                Route::post('users', [UserController::class, 'datatable'])
                    ->name('users.dt');

                Route::post('roles', [RoleController::class, 'datatable'])
                    ->name('roles.dt');

                Route::post('applications', [ApplicationController::class, 'datatable'])
                    ->name('applications.dt');

                Route::post('entity-applications', [EntityApplicationController::class, 'datatable'])
                    ->name('entity-applications.dt');

                Route::post('devices', [DeviceController::class, 'datatable'])
                    ->name('devices.dt');

                Route::prefix('settings')->name('settings.')->group(function () {
                    Route::post('camera', [\App\Http\Controllers\Settings\CameraSettingsController::class, 'datatable'])->name('cameras.dt');
                    Route::post('notification', [\App\Http\Controllers\Settings\NotificationSettingsController::class, 'datatable'])->name('notification.dt');
                });

                Route::post('trips', [TripController::class, 'datatable'])->name('trips.datatable');
            });

        /*
        |------------------------------------------------------------------
        | Resource Routes
        |------------------------------------------------------------------
        */

        Route::resource('entities', EntityController::class);
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('applications', ApplicationController::class);
        Route::resource('entity-applications', EntityApplicationController::class);
        Route::resource('devices', DeviceController::class);
        Route::get('devices/{device}/stat', [DeviceController::class, 'stat'])
            ->name('device.stat');

        Route::resource('trips', TripController::class);

        Route::prefix('profile')->group(function () {
            Route::get('/', [\App\Http\Controllers\ProfileController::class, 'edit'])
                ->name('profile.edit');
            Route::patch('/', [\App\Http\Controllers\ProfileController::class, 'update'])
                ->name('profile.update');
            Route::delete('/', [\App\Http\Controllers\ProfileController::class, 'destroy'])
                ->name('profile.destroy');
        });

        Route::prefix('settings')->name('settings.')->group(function () {
            Route::resource(
                'cameras',
                \App\Http\Controllers\Settings\CameraSettingsController::class
            );
            Route::resource(
                'email',
                \App\Http\Controllers\Settings\EmailSettingsController::class
            );
            Route::post('email/disable', [\App\Http\Controllers\Settings\EmailSettingsController::class, 'disable'])
                ->name('email.disable');

            Route::post('email/test', [\App\Http\Controllers\Settings\EmailSettingsController::class, 'sendTestEmail'])
                ->name('email.test');

            Route::resource(
                'notification',
                \App\Http\Controllers\Settings\NotificationSettingsController::class
            );
        });

        Route::prefix('api')->name('api.')->group(function () {

            // route::get('entities', [])
            //     ->name('entities.index');

            route::get('devices', [DeviceController::class, 'apiList'])
                ->name('devices.list');
        });

        /*
        |------------------------------------------------------------------
        | Logout (explicit & safe)
        |------------------------------------------------------------------
        */
        Route::post('/logout', function () {
            auth()->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            return redirect()->route('login');
        })->name('logout');
    });
