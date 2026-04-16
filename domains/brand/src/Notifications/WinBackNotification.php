<?php

declare(strict_types=1);

namespace Modules\Brand\Notifications;

use Modules\Brand\Models\CustomerEngagement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class WinBackNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly CustomerEngagement $engagement,
    ) {}

    /** @return list<string> */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $name = property_exists($notifiable, 'first_name') ? $notifiable->first_name : 'there';

        return (new MailMessage)
            ->subject("Hey {$name}, we miss you!")
            ->line("It's been a while since we last saw you.")
            ->line('Come back and see what\'s new — we have updates you will not want to miss.')
            ->action('See what\'s new', url('/'))
            ->line('This is a one-time nudge. We will not spam you.');
    }
}
