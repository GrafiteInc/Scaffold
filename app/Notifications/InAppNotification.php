<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class InAppNotification extends Notification
{
    /**
     * Mark as important.
     *
     * @var bool
     */
    public $is_important;

    /**
     * Message for the notification.
     *
     * @var string
     */
    public $message;

    /**
     * Create a notification instance.
     *
     * @param  string  $message
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Mark as important.
     *
     * @return void
     */
    public function isImportant()
    {
        $this->is_important = true;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        $channels = [
            'database',
        ];

        if ($this->shouldPush($notifiable)) {
            $channels[] = WebPushChannel::class;
        }

        return $channels;
    }

    /**
     * Push only to users who allow it and have registered at least one device.
     *
     * @param  mixed  $notifiable
     * @return bool
     */
    protected function shouldPush($notifiable)
    {
        return (bool) $notifiable->allow_push_notifications
            && method_exists($notifiable, 'pushSubscriptions')
            && $notifiable->pushSubscriptions()->exists();
    }

    public function toDatabase()
    {
        return [
            'is_important' => $this->is_important,
            'message' => $this->message,
        ];
    }

    /**
     * Payload for the service worker's push handler.
     *
     * @param  mixed  $notifiable
     * @return WebPushMessage
     */
    public function toWebPush($notifiable)
    {
        return (new WebPushMessage)
            ->title(config('app.name'))
            ->body(Str::limit(strip_tags($this->message), 200))
            ->icon('/images/icons/icon-192x192.png')
            ->data(['url' => route('user.notifications')])
            ->options([
                'TTL' => 3600,
                'urgency' => $this->is_important ? 'high' : 'normal',
            ]);
    }
}
