<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->uuid('trip_uuid')->unique();
            $table->foreignId('device_id')->constrained('devices')->cascadeOnDelete();
            $table->foreignId('entity_id')->constrained('entities')->cascadeOnDelete();
            $table->string('vechicle_number')->nullable();
            $table->string('direction'); // e.g., 'in' or 'out'
            $table->string('snapshot');
            $table->string('top_image')->nullable();
            $table->string('device_ip')->nullable();
            $table->string('weight');
            $table->text('other_images')->nullable();
            $table->text('ocr_log')->nullable();
            $table->string('application_data')->nullable();
            $table->text('raw_data')->nullable();

            $table->timestamp('device_timestamp')->nullable();
            $table->auditable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
