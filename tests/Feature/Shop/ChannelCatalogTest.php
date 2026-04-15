<?php

declare(strict_types=1);

use Modules\ShopCore\Models\Channel;
use Modules\ShopCore\Models\Currency;
use Modules\ShopCore\Models\Locale;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('channel resolves base currency and default locale like Sylius core channel', function () {
    $currency = Currency::query()->create(['code' => 'EUR']);
    $locale = Locale::query()->create(['code' => 'de_DE']);

    $channel = Channel::query()->create([
        'code' => 'eu',
        'name' => 'EU',
        'base_currency_id' => $currency->id,
        'default_locale_id' => $locale->id,
        'enabled' => true,
    ]);

    $channel->currencies()->attach($currency);
    $channel->locales()->attach($locale);

    $channel->load(['baseCurrency', 'defaultLocale']);

    expect($channel->baseCurrency?->code)->toBe('EUR')
        ->and($channel->defaultLocale?->code)->toBe('de_DE');
});
