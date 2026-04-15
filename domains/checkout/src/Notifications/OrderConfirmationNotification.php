<?php

declare(strict_types=1);

namespace Modules\Checkout\Notifications;

use Modules\Checkout\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class OrderConfirmationNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Order $order,
    ) {}

    /**
     * @return list<string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Order confirmation '.$this->order->number)
            ->line('Thank you for your order.')
            ->line('Order number: '.$this->order->number)
            ->line('Total: '.(string) $this->order->total.' '.$this->order->currency_code);
    }
}

