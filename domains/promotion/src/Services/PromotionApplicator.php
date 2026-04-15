<?php

declare(strict_types=1);

namespace Modules\Promotion\Services;

use Modules\Checkout\Models\Adjustment;
use Modules\Checkout\Models\Order;
use Modules\Promotion\Models\Promotion;
use Modules\Promotion\Models\PromotionAction;
use Modules\Promotion\Models\PromotionRule;

/**
 * Cart promotions: rules (AND) + actions → order adjustments (Sylius-style types).
 */
final class PromotionApplicator
{
    public function apply(Order $order): void
    {
        $this->removeUnlockedPromotionAdjustments($order);
        $order->refresh();

        $order->loadMissing(['items.variant.product.taxons', 'items.units', 'promotionCoupon']);

        if ($order->channel_id === null) {
            return;
        }

        $promotions = Promotion::query()
            ->where('enabled', true)
            ->where(function ($q): void {
                $q->whereNull('usage_limit')
                    ->orWhereColumn('used', '<', 'usage_limit');
            })
            ->where(function ($q): void {
                $q->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q): void {
                $q->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            })
            ->whereHas('channels', fn ($q) => $q->where('shop_channels.id', $order->channel_id))
            ->with(['rules', 'actions'])
            ->orderByDesc('priority')
            ->orderByDesc('id')
            ->get();

        foreach ($promotions as $promotion) {
            if (! $this->passesCouponGate($order, $promotion)) {
                continue;
            }

            if (! $this->rulesPass($order, $promotion)) {
                continue;
            }

            $this->applyActions($order, $promotion);

            if ($promotion->exclusive) {
                break;
            }
        }
    }

    private function removeUnlockedPromotionAdjustments(Order $order): void
    {
        $types = [
            Adjustment::ORDER_PROMOTION_ADJUSTMENT,
            Adjustment::ORDER_ITEM_PROMOTION_ADJUSTMENT,
            Adjustment::ORDER_UNIT_PROMOTION_ADJUSTMENT,
        ];

        $order->adjustments()->whereIn('type', $types)->where('locked', false)->delete();

        foreach ($order->items as $item) {
            $item->adjustments()->whereIn('type', $types)->where('locked', false)->delete();
            foreach ($item->units as $unit) {
                $unit->adjustments()->whereIn('type', $types)->where('locked', false)->delete();
            }
        }
    }

    private function passesCouponGate(Order $order, Promotion $promotion): bool
    {
        if (! $promotion->coupon_based) {
            return true;
        }

        $coupon = $order->promotionCoupon;
        if ($coupon === null || $coupon->promotion_id !== $promotion->id) {
            return false;
        }

        return $coupon->isValid();
    }

    private function rulesPass(Order $order, Promotion $promotion): bool
    {
        foreach ($promotion->rules as $rule) {
            if (! $this->rulePasses($order, $rule)) {
                return false;
            }
        }

        return true;
    }

    private function rulePasses(Order $order, PromotionRule $rule): bool
    {
        $config = $rule->configuration ?? [];

        return match ($rule->type) {
            PromotionRule::TYPE_MINIMUM_ORDER_AMOUNT => $order->items_total >= (int) ($config['amount'] ?? 0),
            PromotionRule::TYPE_CONTAINS_TAXON => $this->orderContainsTaxonCode($order, (string) ($config['taxon_code'] ?? '')),
            PromotionRule::TYPE_CONTAINS_PRODUCT => $this->orderContainsProductCodes($order, $config['product_codes'] ?? []),
            default => false,
        };
    }

    private function orderContainsTaxonCode(Order $order, string $taxonCode): bool
    {
        if ($taxonCode === '') {
            return false;
        }

        foreach ($order->items as $item) {
            $product = $item->variant?->product;
            if ($product === null) {
                continue;
            }

            if ($product->taxons->contains('code', $taxonCode)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  list<string>|array<int, string>  $codes
     */
    private function orderContainsProductCodes(Order $order, array $codes): bool
    {
        if ($codes === []) {
            return false;
        }

        $set = array_flip($codes);

        foreach ($order->items as $item) {
            $code = $item->variant?->product?->code;
            if ($code !== null && isset($set[$code])) {
                return true;
            }
        }

        return false;
    }

    private function applyActions(Order $order, Promotion $promotion): void
    {
        foreach ($promotion->actions as $action) {
            $this->applyAction($order, $promotion, $action);
        }
    }

    private function applyAction(Order $order, Promotion $promotion, PromotionAction $action): void
    {
        $config = $action->configuration ?? [];

        match ($action->type) {
            PromotionAction::TYPE_ORDER_FIXED_DISCOUNT => $this->applyFixedDiscount($order, $promotion, (int) ($config['amount'] ?? 0)),
            PromotionAction::TYPE_ORDER_PERCENTAGE_DISCOUNT => $this->applyPercentageDiscount($order, $promotion, (float) ($config['percentage'] ?? 0)),
            default => null,
        };
    }

    private function applyFixedDiscount(Order $order, Promotion $promotion, int $amountMinor): void
    {
        if ($amountMinor <= 0) {
            return;
        }

        $order->adjustments()->create([
            'type' => Adjustment::ORDER_PROMOTION_ADJUSTMENT,
            'label' => $promotion->name,
            'amount' => -$amountMinor,
            'neutral' => false,
            'origin_code' => $promotion->code,
            'details' => ['promotion_id' => $promotion->id],
        ]);
    }

    private function applyPercentageDiscount(Order $order, Promotion $promotion, float $fraction): void
    {
        if ($fraction <= 0) {
            return;
        }

        $discount = (int) round($order->items_total * $fraction);
        if ($discount <= 0) {
            return;
        }

        $order->adjustments()->create([
            'type' => Adjustment::ORDER_PROMOTION_ADJUSTMENT,
            'label' => $promotion->name,
            'amount' => -$discount,
            'neutral' => false,
            'origin_code' => $promotion->code,
            'details' => ['promotion_id' => $promotion->id, 'percentage' => $fraction],
        ]);
    }
}
