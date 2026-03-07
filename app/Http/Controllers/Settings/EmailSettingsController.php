<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\EmailSetting;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Mail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class EmailSettingsController extends Controller
{
    protected string $module = 'settings.email';

    /**
     * Show email settings page
     */
    public function index()
    {
        $this->pageData['pageTitle'] = 'Email Settings';
        $this->pageData['email'] = EmailSetting::first();

        return $this->view('index', $this->pageData);
    }

    /**
     * Show create / edit form
     */
    public function edit()
    {
        $this->pageData['email'] = EmailSetting::first();

        return $this->view('edit', $this->pageData);
    }

    /**
     * Store or update email settings (single row)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mailer' => 'required|string|in:smtp,ses,sendmail,log,array',
            'host' => 'nullable|string|max:255',
            'port' => 'nullable|integer|min:1|max:65535',
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string|max:255',
            'encryption' => 'nullable|in:tls,ssl',
            'from_address' => 'nullable|email|max:255',
            'from_name' => 'nullable|string|max:255',
            'options' => 'nullable|array',
            'is_active' => 'sometimes|boolean',
        ]);

        DB::transaction(function () use ($validated) {

            $email = EmailSetting::first();

            // Preserve password if not provided
            if ($email && empty($validated['password'])) {
                $validated['password'] = $email->password;
            }

            EmailSetting::updateOrCreate(
                ['id' => optional($email)->id],
                [
                    'mailer' => $validated['mailer'],
                    'host' => $validated['host'] ?? null,
                    'port' => $validated['port'] ?? null,
                    'username' => $validated['username'] ?? null,
                    'password' => $validated['password'] ?? null,
                    'encryption' => $validated['encryption'] ?? null,
                    'from_address' => $validated['from_address'] ?? null,
                    'from_name' => $validated['from_name'] ?? null,
                    'options' => $validated['options'] ?? null,
                    'is_active' => $validated['is_active'] ?? true,
                ]
            );

            // Clear cached mail config if any
            Cache::forget('global:email_settings');
        });

        return response()->json([
            'status' => true,
            'message' => 'Email settings saved successfully',
        ]);
    }

    /**
     * Disable email sending (soft)
     */
    public function disable()
    {
        EmailSetting::query()->update(['is_active' => false]);

        Cache::forget('global:email_settings');

        return response()->json([
            'status' => true,
            'message' => 'Email sending disabled',
        ]);
    }


    /**
     * Send test email
     */
    public function sendTestEmail(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $emailSetting = EmailSetting::active();

        if (!$emailSetting) {
            return response()->json([
                'status' => false,
                'message' => 'Email settings not configured',
            ], 422);
        }

        if (!$emailSetting->is_active) {
            return response()->json([
                'status' => false,
                'message' => 'Email sending is disabled',
            ], 422);
        }

        try {
            Mail::raw(
                "✅ This is a test email from your IoT Cloud Platform.\n\nIf you received this email, SMTP configuration is working correctly.",
                function ($message) use ($validated) {
                    $message->to($validated['email'])
                        ->subject('Test Email – Mail Configuration');
                }
            );
        } catch (TransportExceptionInterface $e) {
            return response()->json([
                'status' => false,
                'message' => 'Mail transport error: ' . $e->getMessage(),
            ], 500);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Unexpected error: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => true,
            'message' => 'Test email sent successfully',
        ]);
    }

}
