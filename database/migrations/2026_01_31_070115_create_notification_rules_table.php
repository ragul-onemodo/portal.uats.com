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
        Schema::create('notification_rules', function (Blueprint $table) {
            $table->id();

            // Scope of rule
            $table->string('target_type');
            // entity | device | sensor

            $table->unsignedBigInteger('target_id');

            // Optional event filter
            $table->string('event')->nullable();
            // null = all events for this target

            // Channel
            $table->string('channel');
            // email (future: sms, push)

            $table->boolean('is_active')->default(true);

            $table->auditable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_rules');
    }
};
