<?php

declare(strict_types=1);

use Modules\ShopCore\Http\Middleware\ResolveShopContext;
use Modules\ShopCore\Models\Channel;
use Modules\ShopCore\Models\Currency;
use Modules\ShopCore\Models\Locale;
use Modules\ShopCore\Services\ShopContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

uses(RefreshDatabase::class);

test('shop context middleware resolves channel and locale from headers', function () {
    $currency = Currency::query()->create(['code' => 'USD']);
    $locale = Locale::query()->create(['code' => 'en_US']);

    Channel::query()->create([
        'code' => 'web-us',
        'name' => 'US Web',
        'base_currency_id' => $currency->id,
        'default_locale_id' => $locale->id,
        'enabled' => true,
    ]);

    $request = Request::create('/api/v1/shop/products', 'GET');
    $request->headers->set('X-Channel-Code', 'web-us');

    $middleware = app(ResolveShopContext::class);
    $response = $middleware->handle($request, static fn () => new Response('ok', 200));

    expect($response->getStatusCode())->toBe(200)
        ->and(app(ShopContext::class)->channel()?->code)->toBe('web-us')
        ->and(app(ShopContext::class)->localeCode())->toBe('en_US')
        ->and(app()->getLocale())->toBe('en_US');
});

