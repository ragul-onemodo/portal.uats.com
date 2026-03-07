<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\TripCreated;
use App\Http\Controllers\Controller;
use App\Models\Trip;
use DB;
use Illuminate\Http\Request;
use Str;
use Validator;

class TripController extends Controller
{
    public function store(Request $request)
    {
        /** @var \App\Models\Device $device */
        $device = $request->attributes->get('device');

        if (! $device) {
            return response()->json([
                'error' => 'Device context missing',
            ], 500);
        }

        // directory_path
        $entity = $device?->entity;
        $fileUploadService = new \App\Services\FileUploadService($entity->directory_path, $device->device_uid);

        $now = now();

        /*
         |------------------------------------------------------------
         | 1. Validate incoming payload
         |------------------------------------------------------------
         */

        $imageFileSizeLimitMB = config('edgehub.image_upload_size_mb', 5);

        $validator = Validator::make($request->all(), [
            'direction' => [
                'required',
                'string',
                'in:in,out',
            ],

            'vechicle_number' => [
                'required',
                'string',
            ],

            'snapshot' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:'.($imageFileSizeLimitMB * 1024), // 5MB (adjust later)
            ],

            'top_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:'.($imageFileSizeLimitMB * 1024), // 5MB (adjust later)
            ],

            'weight' => [
                'required',
                'numeric',
                'min:0',
            ],

            'device_timestamp' => [
                'required',
                'date',
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        /*
         |------------------------------------------------------------
         | 2. Persist trip (hot path only)
         |------------------------------------------------------------
         */
        $trip = DB::transaction(function () use ($request, $device, $now, $fileUploadService) {

            $snapshotPath = $fileUploadService->uploadImage($request->snapshot);
            $topImagePath = $request->top_image ? $fileUploadService->uploadImage($request->top_image) : null;

            $otherImages = $request->other_images ? $fileUploadService->uploadImages($request->other_images) : [];

            return Trip::create([
                'trip_uuid' => Str::uuid(),

                'device_id' => $device->id,
                'vechicle_number' => $request->vechicle_number, // extracted async

                'entity_id' => $device->entity_id,

                'direction' => $request->direction,
                'snapshot' => $snapshotPath,
                'top_image' => $topImagePath,

                'device_ip' => $request->ip(),
                'weight' => $request->weight,

                'other_images' => $otherImages ? json_encode($otherImages) : null,

                'raw_data' => json_encode([
                    'headers' => $request->headers->all(),
                    'payload' => $request->all(),
                ]),

                'device_timestamp' => $request->device_timestamp,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        });

        /*
         |------------------------------------------------------------
         | 3. Dispatch async processing event
         |------------------------------------------------------------
         */
        // event(new TripCreated($trip));

        /*
         |------------------------------------------------------------
         | 4. Lightweight ACK to edge hub
         |------------------------------------------------------------
         */
        return response()->json([
            'status' => true,
            'trip_uuid' => $trip->trip_uuid,
            'server_time' => $now->toIso8601String(),
        ], 201);
    }
}
