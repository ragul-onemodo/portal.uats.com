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
        Schema::create('device_system_stats', function (Blueprint $table) {
            $table->id();

            // Relation
            $table->foreignId('device_id')
                ->constrained('devices')
                ->cascadeOnDelete()
                ->unique();

            // Firmware / OS
            $table->string('firmware_version')->nullable();
            $table->string('firmware_build')->nullable();
            $table->string('os_name')->nullable();
            $table->string('os_version')->nullable();
            $table->string('kernel_version')->nullable();

            // Hardware info
            $table->string('cpu_model')->nullable();
            $table->unsignedTinyInteger('cpu_cores')->nullable();
            $table->unsignedInteger('total_ram_mb')->nullable();
            $table->unsignedInteger('total_storage_mb')->nullable();

            // Runtime usage (percentages)
            $table->decimal('cpu_usage_percent', 5, 2)->nullable();
            $table->decimal('ram_usage_percent', 5, 2)->nullable();
            $table->decimal('storage_usage_percent', 5, 2)->nullable();

            // Runtime
            $table->unsignedBigInteger('uptime_seconds')->nullable();
            $table->string('cpu_temperature')->nullable();

            // Sensors & network
            $table->json('connected_sensors')->nullable();
            $table->json('network_interfaces')->nullable();

            // Meta
            $table->timestamp('last_reported_at')->nullable();

            $table->auditable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_system_stats');
    }
};
