<?php

namespace App\Listeners;

use App\Events\VehicleNumberExtracted;
use App\Models\TripOcrResult;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PersistVehicleNumber
{

    use InteractsWithQueue;

    /**
     * Maximum number of attempts
     */
    public int $tries = 5;


    /**
     * Backoff strategy (seconds)
     * Progressive delay to avoid hammering DB
     */
    public array $backoff = [10, 30, 60, 120, 300];


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
    public function handle(VehicleNumberExtracted $event): void
    {
        //

        $trip = $event->trip;

        // Decide acceptance based on confidence
        if ($event->plate && $event->confidence >= config('edgehub.ocr.min_confidence', 80)) {

            $status = 'accepted';

            $trip->update([
                'vechicle_number' => $event->plate,
                'ocr_log' => json_encode([
                    'confidence' => $event->confidence,
                    'engine' => 'paddleocr',
                ]),
            ]);
        } else {
            // Optional: mark as suspicious / pending review
            $trip->update([
                'ocr_log' => json_encode([
                    'confidence' => $event->confidence,
                    'engine' => 'paddleocr',
                    'status' => 'low_confidence',
                ]),
            ]);
        }

        $status = 'low_confidence';


        TripOcrResult::create([
            'trip_id' => $trip->id,
            'plate' => $event->plate,
            'confidence' => $event->confidence,
            'engine' => 'paddleocr',
            'status' => $status,
            'raw_result' => [
                'plate' => $event->plate,
                'confidence' => $event->confidence,
            ],
            'processed_at' => now(),
        ]);

    }


    /**
     * Called when the job fails permanently
     */
    public function failed(VehicleNumberExtracted $event, \Throwable $exception): void
    {
        \Log::error('VehicleNumber persistence failed', [
            'trip_id' => $event->trip->id,
            'exception' => $exception->getMessage(),
        ]);
    }

}
