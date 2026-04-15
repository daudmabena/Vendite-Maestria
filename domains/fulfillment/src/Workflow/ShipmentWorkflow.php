<?php

declare(strict_types=1);

namespace Modules\Fulfillment\Workflow;

use Modules\Fulfillment\Models\Shipment;
use Modules\Checkout\Notifications\ShipmentShippedNotification;
use Modules\Fulfillment\Workflow\Enums\ShipmentState;

/**
 * Shipment lifecycle while order is being prepared and shipped.
 */
final class ShipmentWorkflow
{
    public function current(Shipment $shipment): ShipmentState
    {
        $state = ShipmentState::tryFromStored($shipment->state);
        if ($state === null) {
            throw InvalidStateTransitionException::unknownStoredState('shipment', (string) $shipment->state);
        }

        return $state;
    }

    /**
     * During cart / checkout: shipment exists but is not yet picked.
     */
    public function markCart(Shipment $shipment): void
    {
        $from = $this->current($shipment);
        if (in_array($from, [ShipmentState::Shipped, ShipmentState::Cancelled], true)) {
            throw InvalidStateTransitionException::forShipment($from, ShipmentState::Cart);
        }

        $shipment->update(['state' => ShipmentState::Cart->value]);
    }

    /**
     * Warehouse picked / ready to ship.
     */
    public function markReady(Shipment $shipment): void
    {
        $from = $this->current($shipment);
        if ($from !== ShipmentState::Cart) {
            throw InvalidStateTransitionException::forShipment($from, ShipmentState::Ready);
        }

        $shipment->update(['state' => ShipmentState::Ready->value]);
    }

    /**
     * Handed to carrier.
     */
    public function ship(Shipment $shipment): void
    {
        $from = $this->current($shipment);
        if ($from !== ShipmentState::Ready) {
            throw InvalidStateTransitionException::forShipment($from, ShipmentState::Shipped);
        }

        $shipment->update([
            'state' => ShipmentState::Shipped->value,
            'shipped_at' => $shipment->shipped_at ?? now(),
        ]);
        $shipment->refresh();
        $shipment->order?->customer?->user?->notify(new ShipmentShippedNotification($shipment));
    }

    public function cancel(Shipment $shipment): void
    {
        $from = $this->current($shipment);
        if ($from === ShipmentState::Shipped) {
            throw InvalidStateTransitionException::forShipment($from, ShipmentState::Cancelled);
        }

        $shipment->update(['state' => ShipmentState::Cancelled->value]);
    }
}
