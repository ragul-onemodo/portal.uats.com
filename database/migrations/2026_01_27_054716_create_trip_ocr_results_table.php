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
        Schema::create('trip_ocr_results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('trip_id')
                ->constrained('trips')
                ->cascadeOnDelete();

            $table->string('plate')->nullable();
            $table->float('confidence')->default(0);

            $table->string('engine')->default('paddleocr');
            $table->string('status'); // accepted | low_confidence | failed

            $table->json('raw_result')->nullable(); // full OCR payload
            $table->timestamp('processed_at');

            $table->auditable();

            $table->index(['trip_id', 'confidence']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_ocr_results');
    }
};
