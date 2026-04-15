<?php

declare(strict_types=1);

use Modules\Customer\Http\Controllers\AddressController;
use Modules\Checkout\Http\Controllers\CartController;
use Modules\Checkout\Http\Controllers\AdjustmentController;
use Modules\Customer\Http\Controllers\AuthController;
use Modules\ShopCore\Http\Controllers\ChannelController;
use Modules\Content\Http\Controllers\ContactMessageController;
use Modules\ShopCore\Http\Controllers\CountryController;
use Modules\ShopCore\Http\Controllers\CurrencyController;
use Modules\Customer\Http\Controllers\CustomerController;
use Modules\Customer\Http\Controllers\CustomerGroupController;
use Modules\ShopCore\Http\Controllers\LocaleController;
use Modules\Checkout\Http\Controllers\OrderController;
use Modules\Checkout\Http\Controllers\OrderItemController;
use Modules\Checkout\Http\Controllers\OrderItemUnitController;
use Modules\Checkout\Http\Controllers\PaymentController;
use Modules\Checkout\Http\Controllers\PaymentMethodController;
use Modules\Catalog\Http\Controllers\ProductReviewController;
use Modules\Promotion\Http\Controllers\PromotionActionController;
use Modules\Promotion\Http\Controllers\PromotionController;
use Modules\Promotion\Http\Controllers\PromotionCouponController;
use Modules\Promotion\Http\Controllers\PromotionRuleController;
use Modules\ShopCore\Http\Controllers\ProvinceController;
use Modules\Fulfillment\Http\Controllers\ShipmentController;
use Modules\Fulfillment\Http\Controllers\ShipmentUnitController;
use Modules\ShopCore\Http\Controllers\ShippingCategoryController;
use Modules\Fulfillment\Http\Controllers\ShippingMethodController;
use Modules\ShopCore\Http\Controllers\TaxCategoryController;
use Modules\ShopCore\Http\Controllers\TaxRateController;
use Modules\ShopCore\Http\Controllers\ZoneController;
use Modules\ShopCore\Http\Controllers\ZoneMemberController;
use Illuminate\Support\Facades\Route;
use Modules\Catalog\Http\Controllers\ChannelPricingController;

Route::prefix('v1/shop')->group(function (): void {
    Route::post('carts', [CartController::class, 'store']);
    Route::get('carts/{token}', [CartController::class, 'show']);

    Route::prefix('auth')->group(function (): void {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function (): void {
            Route::get('me', [AuthController::class, 'me']);
            Route::post('logout', [AuthController::class, 'logout']);
        });
    });

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::post('carts/{token}/items', [CartController::class, 'addItem']);
        Route::patch('carts/{token}/items/{item}', [CartController::class, 'updateItem']);
        Route::delete('carts/{token}/items/{item}', [CartController::class, 'removeItem']);
        Route::post('carts/{token}/coupon', [CartController::class, 'applyCoupon']);
        Route::delete('carts/{token}/coupon', [CartController::class, 'removeCoupon']);
        Route::put('carts/{token}/shipping-method', [CartController::class, 'updateShipping']);
        Route::post('carts/{token}/checkout', [CartController::class, 'checkout']);
        Route::post('product-reviews', [ProductReviewController::class, 'store']);
    });

    Route::post('contact-messages', [ContactMessageController::class, 'store']);

    /** @var list<array{0: string, 1: class-string}> $publicResources */
    $publicResources = [
        ['adjustments', AdjustmentController::class],
        ['channel-pricings', ChannelPricingController::class],
        ['channels', ChannelController::class],
        ['countries', CountryController::class],
        ['currencies', CurrencyController::class],
        ['customer-groups', CustomerGroupController::class],
        ['locales', LocaleController::class],
        ['payment-methods', PaymentMethodController::class],
        ['product-reviews', ProductReviewController::class],
        ['promotion-actions', PromotionActionController::class],
        ['promotion-coupons', PromotionCouponController::class],
        ['promotions', PromotionController::class],
        ['promotion-rules', PromotionRuleController::class],
        ['provinces', ProvinceController::class],
        ['shipment-units', ShipmentUnitController::class],
        ['shipping-categories', ShippingCategoryController::class],
        ['shipping-methods', ShippingMethodController::class],
        ['tax-categories', TaxCategoryController::class],
        ['tax-rates', TaxRateController::class],
        ['zone-members', ZoneMemberController::class],
        ['zones', ZoneController::class],
    ];

    foreach ($publicResources as [$uri, $controller]) {
        Route::apiResource($uri, $controller)
            ->only(['index', 'show'])
            ->parameters([$uri => 'id']);
    }

    /** @var list<array{0: string, 1: class-string}> $protectedResources */
    $protectedResources = [
        ['addresses', AddressController::class],
        ['customers', CustomerController::class],
        ['orders', OrderController::class],
    ];

    Route::middleware('auth:sanctum')->group(function () use ($protectedResources): void {
        foreach ($protectedResources as [$uri, $controller]) {
            Route::apiResource($uri, $controller)->parameters([$uri => 'id']);
        }
    });

    /** @var list<array{0: string, 1: class-string}> $adminResources */
    $adminResources = [
        ...array_values(array_filter(
            $publicResources,
            static fn (array $resource): bool => $resource[0] !== 'product-reviews',
        )),
        ['order-items', OrderItemController::class],
        ['order-item-units', OrderItemUnitController::class],
        ['payments', PaymentController::class],
        ['shipments', ShipmentController::class],
    ];

    Route::middleware(['auth:sanctum', 'admin'])->group(function () use ($adminResources): void {
        foreach ($adminResources as [$uri, $controller]) {
            Route::apiResource($uri, $controller)
                ->only(['store', 'update', 'destroy'])
                ->parameters([$uri => 'id']);
        }

        Route::apiResource('product-reviews', ProductReviewController::class)
            ->only(['update', 'destroy'])
            ->parameters(['product-reviews' => 'id']);

        Route::apiResource('contact-messages', ContactMessageController::class)
            ->only(['index', 'show', 'update', 'destroy'])
            ->parameters(['contact-messages' => 'id']);
    });
});
