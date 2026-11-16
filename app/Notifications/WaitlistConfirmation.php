<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WaitlistConfirmation extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public ?string $name = null,
    ) {}

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toMail($notifiable): MailMessage
    {
        $app = config('app.name');

        return (new MailMessage)
            ->subject('You’re on the '.$app.' waitlist')
            ->greeting($this->name ? 'Hello '.$this->name.',' : 'Hello,')
            ->line('Thanks for your interest in '.$app.'. You’re on the list for early access.')
            ->line('We’re letting people in gradually, and we’ll email you an invite as soon as a spot opens up.')
            ->line('No need to do anything else in the meantime.');
    }
}
