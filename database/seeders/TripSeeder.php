<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trip;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TripSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 20; $i++) {

            Trip::create([
                'trip_uuid'        => Str::uuid(),
                'device_id'        => 1,
                'entity_id'        => 1,
                'vechicle_number'  => 'TN'.rand(10,99).'AB'.rand(1000,9999),
                'direction'        => rand(0,1) ? 'IN' : 'OUT',
                'snapshot'         => str::uuid().'.jpg',
                'top_image'        => null,
                'device_ip'        => '192.168.1.'.rand(1,255),
                'weight'           => rand(1000, 50000),
                'other_images'     => null,
                'ocr_log'          => json_encode(['plate' => 'TN'.rand(10,99).'AB'.rand(1000,9999)]),
                'application_data' => json_encode(['status' => 'completed']),
                'raw_data'         => json_encode(['raw' => 'sample data']),
                'device_timestamp' => Carbon::now()->subDays(rand(0,10)),
                'deleted'          => 0,
                'created_by'       => 1,
                'updated_by'       => null,
                'deleted_by'       => null,
            ]);
        }
    }
}
