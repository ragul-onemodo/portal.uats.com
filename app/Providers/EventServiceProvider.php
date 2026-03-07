<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{


    protected $listen = [
        // Event::class => [
        //     Listener::class,
        // ],

        \App\Events\TripCreated::class => [
            \App\Listeners\ExtractVehicleNumber::class,
        ],

        \App\Events\VehicleNumberExtracted::class => [
            \App\Listeners\PersistVehicleNumber::class,
        ],
    ];


    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
