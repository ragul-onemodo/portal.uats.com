<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            // Scope
            $table->string('source_type');
            // entity | device | sensor

            $table->unsignedBigInteger('source_id');

            // Event identity
            $table->string('event');
            // e.g: temperature_threshold, device_offline, payment_failed

            $table->json('payload')->nullable();

            $table->timestamp('occurred_at');

            $table->auditable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
