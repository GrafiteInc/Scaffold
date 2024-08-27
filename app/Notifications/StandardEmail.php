<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StandardEmail extends Notification implements ShouldQueue
{
    use Queueable;

    public $name;

    public $subject;

    public $message;

    /**
     * Create a notification instance.
     *
     * @param  string  $name
     * @param  string  $subject
     * @param  string  $message
     */
    public function __construct($name, $subject, $message)
    {
        $this->name = $name;
        $this->subject = $subject;
        $this->message = $message;
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
            'mail',
        ];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            // ->theme('custom')
            ->subject($this->subject)
            ->greeting('Hello '.$this->name)
            ->line($this->message)
            ->action('Login', url('login'));
    }
}
