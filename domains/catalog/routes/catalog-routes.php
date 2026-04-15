<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Catalog\Http\Controllers\ChannelPricingController;
use Modules\Catalog\Http\Controllers\ProductAssociationController;
use Modules\Catalog\Http\Controllers\ProductAssociationTypeController;
use Modules\Catalog\Http\Controllers\ProductAttributeController;
use Modules\Catalog\Http\Controllers\ProductAttributeValueController;
use Modules\Catalog\Http\Controllers\ProductImageController;
use Modules\Catalog\Http\Controllers\ProductOptionController;
use Modules\Catalog\Http\Controllers\ProductOptionValueController;
use Modules\Catalog\Http\Controllers\ProductController;
use Modules\Catalog\Http\Controllers\ProductVariantController;
use Modules\Catalog\Http\Controllers\TaxonController;

Route::prefix('api/v1/shop')->group(function (): void {
    /** @var list<array{0: string, 1: class-string}> $publicResources */
    $publicResources = [
        ['channel-pricings', ChannelPricingController::class],
        ['product-associations', ProductAssociationController::class],
        ['product-association-types', ProductAssociationTypeController::class],
        ['product-attribute-values', ProductAttributeValueController::class],
        ['product-attributes', ProductAttributeController::class],
        ['product-images', ProductImageController::class],
        ['product-option-values', ProductOptionValueController::class],
        ['product-options', ProductOptionController::class],
        ['products', ProductController::class],
        ['product-variants', ProductVariantController::class],
        ['taxons', TaxonController::class],
    ];

    foreach ($publicResources as [$uri, $controller]) {
        Route::apiResource($uri, $controller)
            ->only(['index', 'show'])
            ->parameters([$uri => 'id']);
    }

    Route::middleware(['auth:sanctum', 'admin'])->group(function () use ($publicResources): void {
        foreach ($publicResources as [$uri, $controller]) {
            Route::apiResource($uri, $controller)
                ->only(['store', 'update', 'destroy'])
                ->parameters([$uri => 'id']);
        }
    });
});
