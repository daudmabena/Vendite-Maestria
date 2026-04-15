<?php

declare(strict_types=1);

namespace Modules\Checkout\Notifications;

use Modules\Fulfillment\Models\Shipment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class ShipmentShippedNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Shipment $shipment,
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
        $order = $this->shipment->order;

        return (new MailMessage)
            ->subject('Shipment update for order '.$order?->number)
            ->line('Your shipment has been dispatched.')
            ->line('Order number: '.($order?->number ?? 'N/A'))
            ->line('Tracking: '.($this->shipment->tracking ?? 'N/A'));
    }
}

