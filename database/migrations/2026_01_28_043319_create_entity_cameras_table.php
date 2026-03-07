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
        Schema::create('entity_cameras', function (Blueprint $table) {
            $table->id();

            $table->foreignId('entity_id')
                ->constrained('entities')
                ->cascadeOnDelete();

            $table->string('name');

            $table->string('ip_address');

            $table->string('username')->nullable();
            $table->text('password')->nullable(); // encrypted

            $table->string('snapshot_url');

            // Explicit role flags
            $table->boolean('is_primary')->default(false);
            $table->boolean('is_secondary')->default(false);

            $table->boolean('is_active')->default(true);

            $table->auditable();

            $table->index(['entity_id', 'is_primary']);
            $table->index(['entity_id', 'is_secondary']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entity_cameras');
    }
};
