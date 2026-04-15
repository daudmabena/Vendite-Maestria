<?php

declare(strict_types=1);

namespace Modules\Checkout\Services;

use Modules\Checkout\Models\Adjustment;
use Modules\Checkout\Models\Order;
use Modules\ShopCore\Models\TaxRate;
use Modules\Checkout\Workflow\Enums\OrderState;
use Modules\Promotion\Services\PromotionApplicator;
use Modules\Fulfillment\Services\FlatRateShippingCalculator;
use Modules\Promotion\Services\DefaultTaxCalculator;
use Illuminate\Support\Facades\DB;

/**
 * Recomputes promotion, shipping, and tax adjustments for an open cart order.
 */
final class CartTotalsRefresher
{
    public function __construct(
        private readonly PromotionApplicator $promotionApplicator,
        private readonly DefaultTaxCalculator $taxCalculator,
    ) {}

    public function refresh(Order $order): void
    {
        if (OrderState::tryFromStored($order->state) !== OrderState::Cart || $order->isCheckoutCompleted()) {
            return;
        }

        DB::transaction(function () use ($order): void {
            $order->refresh();
            $order->load(['items.units', 'items.variant.product.taxons', 'shipments.method', 'channel']);

            $this->promotionApplicator->apply($order);

            $order->refresh();
            $order->load(['items.units', 'items.variant.product.taxons', 'shipments.method', 'channel']);

            $this->syncShippingAdjustment($order);
            $this->syncTaxAdjustments($order);

            $order->refresh();
            $order->recalculateItemsTotal();
        });
    }

    private function syncShippingAdjustment(Order $order): void
    {
        $order->adjustments()
            ->where('type', Adjustment::SHIPPING_ADJUSTMENT)
            ->where('locked', false)
            ->delete();

        $shipment = $order->shipments()->orderBy('id')->first();
        if ($shipment === null || $shipment->shipping_method_id === null) {
            $order->recalculateAdjustmentsTotal();

            return;
        }

        $method = $shipment->method;
        if ($method === null) {
            $order->recalculateAdjustmentsTotal();

            return;
        }

        $amount = FlatRateShippingCalculator::amountMinor($method);
        if ($amount <= 0) {
            $order->recalculateAdjustmentsTotal();

            return;
        }

        $order->adjustments()->create([
            'type' => Adjustment::SHIPPING_ADJUSTMENT,
            'label' => $method->name,
            'amount' => $amount,
            'neutral' => false,
            'origin_code' => $method->code,
            'details' => ['shipping_method_id' => $method->id],
        ]);
    }

    private function syncTaxAdjustments(Order $order): void
    {
        $order->adjustments()
            ->where('type', Adjustment::TAX_ADJUSTMENT)
            ->where('locked', false)
            ->delete();

        $taxMinor = 0;

        $order->loadMissing('items.variant.taxCategory');

        foreach ($order->items as $item) {
            $variant = $item->variant;
            if ($variant === null || $variant->tax_category_id === null) {
                continue;
            }

            $rates = $this->activeTaxRatesForCategory((int) $variant->tax_category_id);
            foreach ($rates as $rate) {
                if ($rate->included_in_price) {
                    continue;
                }

                $taxMinor += $this->taxCalculator->calculateExclusiveMinorUnits((int) $item->total, $rate);
            }
        }

        if ($taxMinor <= 0) {
            $order->recalculateAdjustmentsTotal();

            return;
        }

        $order->adjustments()->create([
            'type' => Adjustment::TAX_ADJUSTMENT,
            'label' => 'Tax',
            'amount' => $taxMinor,
            'neutral' => false,
            'details' => ['source' => 'cart_totals_refresher'],
        ]);
    }

    /**
     * @return \Illuminate\Support\Collection<int, TaxRate>
     */
    private function activeTaxRatesForCategory(int $taxCategoryId)
    {
        return TaxRate::query()
            ->where('tax_category_id', $taxCategoryId)
            ->where('calculator', 'default')
            ->where(function ($q): void {
                $q->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function ($q): void {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            })
            ->get();
    }
}
