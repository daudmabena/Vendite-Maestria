<?php

declare(strict_types=1);

namespace Modules\Brand\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class MilestoneNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $milestone,
        private readonly string $message,
    ) {}

    /** @return list<string> */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("You've reached a milestone: {$this->milestone}")
            ->line($this->message)
            ->action('Claim your reward', url('/'));
    }
}
