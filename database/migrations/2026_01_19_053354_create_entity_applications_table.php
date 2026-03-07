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
        Schema::create('entity_applications', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('application_id');
            $table->unsignedBigInteger('entity_id');

            $table->text('company_reference');

            $table->boolean('is_active')->default(true);

            $table->auditable();

            $table->unique(
                ['application_id', 'entity_id'],
                'application_entity_unique'
            );

            $table->foreign('application_id')
                ->references('id')
                ->on('applications')
                ->cascadeOnDelete();

            $table->foreign('entity_id')
                ->references('id')
                ->on('entities')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entity_applications');
    }
};
