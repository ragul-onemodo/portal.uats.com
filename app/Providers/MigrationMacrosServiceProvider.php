<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Schema\Blueprint;

class MigrationMacrosServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Add dualSoftDeletes macro
        Blueprint::macro('status', function () {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            $this->tinyInteger('status')->default(1);
        });

        Blueprint::macro('dualSoftDeletes', function () {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            $this->softDeletes();
            $this->tinyInteger('deleted')->default(0);
        });


        Blueprint::macro('dropDualSoftDeletes', function () {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            $this->dropSoftDeletes();
            $this->dropColumn(columns: 'deleted');
        });

        Blueprint::macro('blameable', function () {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            $this->unsignedBigInteger('created_by')->nullable();
            $this->unsignedBigInteger('updated_by')->nullable();
            $this->unsignedBigInteger('deleted_by')->nullable();
        });

        Blueprint::macro('iotTimestamps', function ($precision = null) {
            /** @var Blueprint $this */
            $this->timestamp('created_at', $precision)->nullable();
            $this->timestamp('updated_at', $precision)->nullable();
        });

        Blueprint::macro('auditable', function () {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            $this->iotTimestamps();
            $this->dualSoftDeletes();
            $this->blameable();
        });

        Blueprint::macro('entity', function () {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            $this->foreignId('entity_id')->constrained('entities')->cascadeOnDelete();
        });


    }

}
