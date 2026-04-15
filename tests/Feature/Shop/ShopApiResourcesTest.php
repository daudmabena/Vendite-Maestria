<?php

declare(strict_types=1);

use Modules\ShopCore\Models\Channel;
use Modules\ShopCore\Models\Currency;
use Modules\ShopCore\Models\Locale;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('shop channels API lists and shows records by id', function () {
    $currency = Currency::query()->create(['code' => 'USD']);
    $locale = Locale::query()->create(['code' => 'en_US']);

    $channel = Channel::query()->create([
        'code' => 'us',
        'name' => 'US',
        'base_currency_id' => $currency->id,
        'default_locale_id' => $locale->id,
        'enabled' => true,
    ]);

    $index = $this->getJson('/api/v1/shop/channels');
    $index->assertOk();
    $index->assertJsonPath('data.0.code', 'us');

    $show = $this->getJson('/api/v1/shop/channels/'.$channel->id);
    $show->assertOk();
    $show->assertJsonPath('code', 'us');
});
