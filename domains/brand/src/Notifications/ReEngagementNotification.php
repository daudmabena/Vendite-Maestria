<?php

declare(strict_types=1);

namespace Modules\Brand\Notifications;

use Modules\Brand\Models\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class ReEngagementNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Campaign $campaign,
    ) {}

    /** @return list<string> */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->campaign->subject ?? 'We have something for you')
            ->line($this->campaign->body ?? 'Check out what\'s new — we think you\'ll love it.')
            ->action('Visit the store', url('/'));
    }
}
