<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserInviteEmail extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;

    public $from;

    public $message;

    public $token;

    /**
     * Create a notification instance.
     *
     * @param string $token
     */
    public function __construct($user, $from, $message, $token)
    {
        $this->user = $user;
        $this->from = $from;
        $this->message = $message;
        $this->token = $token;
    }

    /**
     * Get the notification's channels.
     *
     * @param mixed $notifiable
     *
     * @return array|string
     */
    public function via($notifiable)
    {
        return [
            'mail',
        ];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $app = config('app.name');

        return (new MailMessage())
            ->subject('You’ve Been Invited to Join '.$app)
            ->greeting('Hello '.$this->user)
            ->line($this->from->name.' has invited you to join '.$app.'!')
            ->line($this->message)
            ->line('Click the link below to create your account now!')
            ->action('Login', url('register/invite?email='.urlencode($this->user).'&activate_token='.$this->token));
    }
}
