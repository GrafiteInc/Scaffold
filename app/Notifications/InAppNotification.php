<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class InAppNotification extends Notification
{
    /**
     * Mark as important
     *
     * @var string
     */
    public $is_important;

    /**
     * Message for the notification
     *
     * @var string
     */
    public $message;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Mark as important
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
        return [
            'database',
        ];
    }

    public function toDatabase()
    {
        return [
            'is_important' => $this->is_important,
            'message' => $this->message,
        ];
    }
}
