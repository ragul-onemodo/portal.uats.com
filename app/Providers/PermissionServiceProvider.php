<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;
use App\Permissions\PermissionManifest;
use Illuminate\Support\Facades\Schema;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        if (!Schema::hasTable('permissions')) {
        return;
    }
        // Skip sync in certain environments or if disabled via env
        if (app()->environment('production') && !env('SYNC_PERMISSIONS_ON_BOOT', false)) {
            return;
        }

        $this->syncPermissions();

        // Register the sync command
        if ($this->app->runningInConsole()) {
            $this->commands([
                \App\Console\Commands\SyncPermissions::class,
            ]);
        }
    }

    /**
     * Sync all permissions from PermissionManifest to the database.
     */
    public function syncPermissions()
    {
        $modules = PermissionManifest::all();
        foreach ($modules as $module => $items) {

            foreach ($items as $key => $value) {

                // CASE 1: flat list → ['view', 'create', ...]
                if (is_numeric($key)) {

                    $action = $value;

                    $normalized = strtolower(str_replace(' ', '_', $action));
                    $name = "{$module}.{$normalized}";

                    // \Log::info("Syncing permission: {$name}");

                    Permission::firstOrCreate([
                        'name' => $name,
                        'guard_name' => 'web',
                    ]);

                    continue;
                }

                // CASE 2: nested → ['ledger' => [...actions]]
                $submodule = $key;

                foreach ($value as $action) {

                    $normalized = strtolower(str_replace(' ', '_', $action));
                    $name = "{$module}.{$submodule}.{$normalized}";

                    Permission::firstOrCreate([
                        'name' => $name,
                        'guard_name' => 'web',
                    ]);
                }
            }
        }
    }
}