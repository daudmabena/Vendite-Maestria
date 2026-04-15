<?php

declare(strict_types=1);

use Modules\Catalog\Models\Product;
use Modules\Catalog\Models\ProductImage;
use Modules\Catalog\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('product stores ordered gallery images and variant specific images', function () {
    $product = Product::query()->create(['code' => 'img-demo', 'enabled' => true]);
    $variant = ProductVariant::query()->create(['product_id' => $product->id, 'code' => 'img-demo-v1', 'enabled' => true]);

    ProductImage::query()->create([
        'product_id' => $product->id,
        'product_variant_id' => null,
        'path' => 'products/img-demo/gallery-1.jpg',
        'position' => 0,
        'mime_type' => 'image/jpeg',
    ]);

    ProductImage::query()->create([
        'product_id' => $product->id,
        'product_variant_id' => $variant->id,
        'path' => 'products/img-demo/variants/v1-front.jpg',
        'position' => 0,
        'mime_type' => 'image/jpeg',
    ]);

    $product->refresh();
    $variant->refresh();

    expect($product->images)->toHaveCount(2)
        ->and($product->productLevelImages)->toHaveCount(1)
        ->and($variant->images)->toHaveCount(1)
        ->and($variant->images->first()->path)->toContain('v1-front');
});

test('product image rejects variant from another product', function () {
    $p1 = Product::query()->create(['code' => 'p1', 'enabled' => true]);
    $p2 = Product::query()->create(['code' => 'p2', 'enabled' => true]);
    $v2 = ProductVariant::query()->create(['product_id' => $p2->id, 'code' => 'p2-v', 'enabled' => true]);

    expect(fn () => ProductImage::query()->create([
        'product_id' => $p1->id,
        'product_variant_id' => $v2->id,
        'path' => 'x.jpg',
        'position' => 0,
    ]))->toThrow(InvalidArgumentException::class);
});
