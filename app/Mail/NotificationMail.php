<?php

namespace App\Mail;

use App\Enums\NotificationEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The notification payload.
     */
    public function __construct(public object $notification)
    {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $config = $this->resolveEventConfig();

        return new Envelope(
            subject: $config['subject'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $config = $this->resolveEventConfig();

        return new Content(
            view: $config['view'],
            with: [
                'notification' => $this->notification,
            ]
        );
    }

    /**
     * Attachments.
     */
    public function attachments(): array
    {
        return [];
    }

    /**
     * Resolve subject + view based on event.
     */
    protected function resolveEventConfig(): array
    {
        $event = $this->notification->event ?? null;

        return $this->eventConfig()[$event] ?? [
            'subject' => '🔔 Notification',
            'view' => 'emails.notifications.default',
        ];
    }

    /**
     * Event → Mail config map.
     */
    protected function eventConfig(): array
    {
        return [
            NotificationEvent::DEVICE_OFFLINE => [
                'subject' => '🚨 Device Offline',
                'view' => 'emails.notifications.device_status',
            ],

            NotificationEvent::DEVICE_ONLINE => [
                'subject' => '✅ Device Online',
                'view' => 'emails.notifications.device_status',
            ],

            NotificationEvent::SENSOR_DISCONNECTED => [
                'subject' => '⚠️ Sensor Disconnected',
                'view' => 'emails.notifications.sensor_status',
            ],

            NotificationEvent::SENSOR_RECONNECTED => [
                'subject' => '✅ Sensor Reconnected',
                'view' => 'emails.notifications.sensor_status',
            ],

            NotificationEvent::DEVICE_OVERHEATED => [
                'subject' => '🔥 Device Overheated',
                'view' => 'emails.notifications.device_overheated',
            ],

            NotificationEvent::API_SERVER_ERROR => [
                'subject' => '❌ API Server Error',
                'view' => 'emails.notifications.api_server_error',
            ],

            NotificationEvent::CAMERA_DISCONNECTED => [
                'subject' => '📷 Camera Disconnected',
                'view' => 'emails.notifications.camera_status',
            ],

            NotificationEvent::CAMERA_ONLINE => [
                'subject' => '📷 Camera Online',
                'view' => 'emails.notifications.camera_status',
            ],
        ];
    }
}
