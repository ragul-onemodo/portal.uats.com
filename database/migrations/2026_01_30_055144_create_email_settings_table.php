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
        Schema::create('email_settings', function (Blueprint $table) {
            $table->id();

            // Mailer type
            $table->string('mailer')->default('smtp');
            // smtp | ses | sendmail | log | array

            // SMTP fields (nullable for non-SMTP mailers)
            $table->string('host')->nullable();
            $table->unsignedInteger('port')->nullable();
            $table->string('username')->nullable();
            $table->text('password')->nullable(); // encrypted
            $table->string('encryption')->nullable(); // tls | ssl | null

            // Default sender
            $table->string('from_address')->nullable();
            $table->string('from_name')->nullable();

            // Provider-specific / future options
            $table->json('options')->nullable();

            // Master toggle
            $table->boolean('is_active')->default(true);

            $table->auditable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_settings');
    }
};
