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
        Schema::create('entities', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name', 150);
            $table->string('location', 150)->nullable();

            $table->boolean('is_active')->default(true);
            $table->boolean('integration_enabled')->default(false);
            $table->string('directory_path', 191)->unique();

            $table->auditable();

            $table->index('is_active');
            $table->index('integration_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entities');
    }
};
