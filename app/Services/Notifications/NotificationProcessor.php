<?php

namespace App\Services\Notifications;

use App\Models\Notification;
use App\Models\NotificationRule;
use Mail;

class NotificationProcessor
{
    public function handle(Notification $notification): void
    {
        $rules = NotificationRule::query()
            ->where('is_active', true)
            ->where(function ($q) use ($notification) {
                $q->where(function ($q) use ($notification) {
                    $q->where('target_type', $notification->source_type)
                        ->where('target_id', $notification->source_id);
                });
            })
            // ->where(function ($q) use ($notification) {
            //     $q->whereNull('event')
            //         ->orWhere('event', $notification->event);
            // })
            ->with('recipients')
            ->get();

        if ($rules->isEmpty()) {

            \Log::info('No active notification rules found for notification ID: ' . $notification->id);
            return;
        }

        $this->dispatchByChannel($rules, $notification);
    }

    protected function dispatchByChannel($rules, Notification $notification): void
    {
        $recipients = $rules
            ->flatMap(fn($rule) => $rule->recipients)
            ->unique(fn($r) => $r->recipient_type . ':' . $r->recipient_value);

        // EMAIL
        $emails = $recipients
            ->where('recipient_type', 'email')
            ->pluck('recipient_value')
            ->values()
            ->all();

        if (!empty($emails)) {
            $this->sendEmailNotification($emails, $notification);
        }
    }


    private function sendEmailNotification(array $emails, Notification $notification): void
    {
        // Implementation for sending email notifications


        foreach ($emails as $email) {
            Mail::to($email)->queue(
                new \App\Mail\NotificationMail($notification)
            );
        }
    }
}
