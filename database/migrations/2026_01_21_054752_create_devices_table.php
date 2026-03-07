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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();

            $table->string('device_name', 100);
            $table->string('device_type', 50);

            $table->string('device_uid', 100)->unique();

            $table->unsignedBigInteger('entity_id');

            $table->string('api_key', 191)->unique();

            $table->boolean('is_active')->default(true);

            $table->timestamp('last_heartbeat_at')->nullable();
            $table->timestamp('last_seen_at')->nullable();

            $table->json('last_health_payload')->nullable();

            $table->string('last_ip', 45)->nullable();

            $table->auditable();

            $table->index(['entity_id', 'device_type']);
            $table->index('last_heartbeat_at');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
