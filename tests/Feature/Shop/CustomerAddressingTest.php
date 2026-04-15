<?php

declare(strict_types=1);

use Modules\Customer\Models\Address;
use Modules\ShopCore\Models\Country;
use Modules\Customer\Models\Customer;
use Modules\ShopCore\Models\Zone;
use Modules\ShopCore\Models\ZoneMember;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('customer normalizes email canonical like Sylius', function () {
    $customer = Customer::query()->create([
        'email' => 'Jane.Doe@Example.com',
        'first_name' => 'Jane',
        'last_name' => 'Doe',
    ]);

    expect($customer->fresh()->email_canonical)->toBe('jane.doe@example.com');
});

test('customer default address links and stores country codes like Sylius addressing', function () {
    $customer = Customer::query()->create([
        'email' => 'shopper@example.com',
        'first_name' => 'Sam',
        'last_name' => 'Shopper',
    ]);

    $address = Address::query()->create([
        'customer_id' => $customer->id,
        'first_name' => 'Sam',
        'last_name' => 'Shopper',
        'country_code' => 'US',
        'street' => '1 Market St',
        'city' => 'San Francisco',
        'postcode' => '94105',
    ]);

    $customer->setDefaultAddress($address);

    $customer->refresh();
    expect($customer->default_address_id)->toBe($address->id)
        ->and($customer->defaultAddress?->country_code)->toBe('US')
        ->and($address->fresh()->getFullName())->toBe('Sam Shopper');
});

test('clearing country code on address clears province code per Sylius Address', function () {
    $address = Address::query()->create([
        'country_code' => 'US',
        'province_code' => 'CA',
        'city' => 'LA',
    ]);

    $address->update(['country_code' => null]);

    expect($address->fresh()->province_code)->toBeNull();
});

test('zone holds country-type members like Sylius Zone', function () {
    $zone = Zone::query()->create([
        'code' => 'na',
        'name' => 'North America',
        'type' => Zone::TYPE_COUNTRY,
        'scope' => Zone::SCOPE_ALL,
        'priority' => 10,
    ]);

    ZoneMember::query()->create([
        'zone_id' => $zone->id,
        'code' => 'US',
    ]);

    ZoneMember::query()->create([
        'zone_id' => $zone->id,
        'code' => 'CA',
    ]);

    $zone->load('members');

    expect($zone->members)->toHaveCount(2)
        ->and($zone->members->pluck('code')->sort()->values()->all())->toBe(['CA', 'US']);
});

test('channel can scope to countries like Sylius Core Channel', function () {
    $country = Country::query()->create(['code' => 'DE', 'enabled' => true]);

    $channel = \Modules\ShopCore\Models\Channel::query()->create([
        'code' => 'de-store',
        'name' => 'DE',
        'enabled' => true,
    ]);

    $channel->countries()->attach($country);

    expect($channel->fresh()->countries->first()?->code)->toBe('DE');
});
