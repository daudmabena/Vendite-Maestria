<?php

declare(strict_types=1);

use Modules\Catalog\Models\Product;
use Modules\Catalog\Models\ProductAttribute;
use Modules\Catalog\Models\ProductAttributeValue;
use Modules\Catalog\Models\ProductOption;
use Modules\Catalog\Models\ProductOptionValue;
use Modules\Catalog\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('product options link to products and variants pick one value per option', function () {
    $size = ProductOption::query()->create(['code' => 'size', 'name' => 'Size', 'position' => 0]);
    $s = ProductOptionValue::query()->create([
        'product_option_id' => $size->id,
        'code' => 's',
        'value' => 'Small',
        'position' => 0,
    ]);
    $m = ProductOptionValue::query()->create([
        'product_option_id' => $size->id,
        'code' => 'm',
        'value' => 'Medium',
        'position' => 1,
    ]);

    $product = Product::query()->create(['code' => 'tee', 'enabled' => true]);
    $product->options()->attach($size->id, ['position' => 0]);

    $v1 = ProductVariant::query()->create(['product_id' => $product->id, 'code' => 'tee-s', 'enabled' => true]);
    $v1->setOptionValue($s);

    expect($v1->optionValueForOption($size)?->code)->toBe('s');

    $v1->setOptionValue($m);
    $v1->refresh();

    expect($v1->optionValues)->toHaveCount(1)
        ->and($v1->optionValueForOption($size)?->code)->toBe('m');
});

test('product attributes store typed values and resolve for display', function () {
    $attr = ProductAttribute::query()->create([
        'code' => 'weight_g',
        'name' => 'Weight (g)',
        'type' => ProductAttribute::TYPE_INTEGER,
        'storage_type' => ProductAttribute::STORAGE_INTEGER,
        'position' => 0,
    ]);

    $product = Product::query()->create(['code' => 'mug', 'enabled' => true]);

    $value = ProductAttributeValue::query()->create([
        'product_id' => $product->id,
        'product_attribute_id' => $attr->id,
        'locale' => '',
        'integer_value' => 320,
    ]);

    $product->refresh();

    expect($product->attributeValueFor('weight_g')?->getResolvedValue())->toBe(320);
});
