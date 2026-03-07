<?php

namespace App\Listeners;

use App\Events\VehicleNumberExtracted;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;
use App\Events\TripCreated;

class ExtractVehicleNumber
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TripCreated $event): void
    {

        // dd($event->trip);
        // 
        $trip = $event->trip;

        if (!$trip->snapshot) {
            return;
        }


        try {
            $response = Http::timeout(15)->withoutVerifying()->post(
                config('edgehub.ocr.endpoint'),
                [
                    'image_path' => $trip->snapshot,
                    'trip_uuid' => (string) $trip->trip_uuid, // IMPORTANT
                ]
            );
        } catch (RequestException $e) {
            // Network / timeout / DNS error
            return;
        }

        if (!$response->successful()) {
            return;
        }

        $data = $response->json();

        event(new VehicleNumberExtracted(
            $trip,
            $data['plate'] ?? null,
            $data['confidence'] ?? 0
        ));
    }
}
