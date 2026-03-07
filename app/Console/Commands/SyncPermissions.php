<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Providers\PermissionServiceProvider;
use Illuminate\Foundation\Application;

class SyncPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */


    protected $signature = 'app:sync-permissions';

    /**
     * The console command description.
     * This helps to sync the permissions to the database.
     *
     * @var string
     */
    protected $description = 'This helps to sync the permissions to the database.';

    /**
     * Execute the console command.
     */
    public function handle(Application $app)
    {
        //

        $this->info('Syncing permissions...');

        // app(PermissionServiceProvider::class)->syncPermissions();
        $permissionService = new PermissionServiceProvider($app);
        $permissionService->syncPermissions();

        $this->info('Permissions synced successfully.');

    }
}
