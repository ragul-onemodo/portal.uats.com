<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\EmailSetting;

class EmailSettingsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // DB / table illa na crash aagama stop
        if (!Schema::hasTable('email_settings')) {
            return;
        }

       
        // if ($this->app->runningInConsole()) {
        //     return;
        // }

        $email = EmailSetting::active();

        if (!$email) {
            return;
        }

        // Disable mail globally
        if (!$email->is_active) {
            config(['mail.default' => 'log']);
            return;
        }

        config([
            'mail.default' => $email->mailer,

            'mail.mailers.smtp.host' => $email->host,
            'mail.mailers.smtp.port' => $email->port,
            'mail.mailers.smtp.username' => $email->username,
            'mail.mailers.smtp.password' => $email->password,
            'mail.mailers.smtp.encryption' => $email->encryption,

            'mail.from.address' => $email->from_address,
            'mail.from.name' => $email->from_name,
        ]);
    }
}
