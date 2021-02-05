<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TwoFactorNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return [
            'mail',
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toMail($notifiable): MailMessage
    {
        $url = route('verification.two-factor');

        return (new MailMessage())
            ->greeting('Hello ' . $notifiable->name)
            ->line('Your two factor code is: ' . $notifiable->two_factor_code)
            ->action('Verify Here', $url)
            ->line('The code will expire in 10 minutes')
            ->line('If you have not tried to login, we recommend changing your password immediately.');
    }
}
