<?php

declare(strict_types=1);

use Modules\Customer\Models\Address;
use Modules\Customer\Models\Customer;
use Modules\Checkout\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('customer can register and fetch own profile', function () {
    $register = $this->postJson('/api/v1/shop/auth/register', [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'first_name' => 'Jane',
        'last_name' => 'Doe',
    ]);

    $register->assertCreated();
    $token = $register->json('token');
    expect($token)->not->toBeEmpty();

    $me = $this
        ->withToken($token)
        ->getJson('/api/v1/shop/auth/me');

    $me->assertOk()
        ->assertJsonPath('data.email', 'jane@example.com')
        ->assertJsonPath('data.first_name', 'Jane');
});

test('customer can only view own order and address resources', function () {
    $u1 = User::factory()->create();
    $u2 = User::factory()->create();

    $c1 = Customer::query()->create(['user_id' => $u1->id, 'email' => $u1->email]);
    $c2 = Customer::query()->create(['user_id' => $u2->id, 'email' => $u2->email]);

    $order1 = Order::query()->create(['customer_id' => $c1->id, 'state' => Order::STATE_CART]);
    $order2 = Order::query()->create(['customer_id' => $c2->id, 'state' => Order::STATE_CART]);

    $address1 = Address::query()->create(['customer_id' => $c1->id, 'city' => 'A']);
    $address2 = Address::query()->create(['customer_id' => $c2->id, 'city' => 'B']);

    $token1 = $u1->createToken('t1')->plainTextToken;

    $listOrders = $this->withToken($token1)->getJson('/api/v1/shop/orders');
    $listOrders->assertOk();
    $ordersJson = json_encode($listOrders->json(), JSON_THROW_ON_ERROR);
    expect($ordersJson)->toContain(sprintf('"id":%d', $order1->id))
        ->not->toContain(sprintf('"id":%d', $order2->id));

    $listAddresses = $this->withToken($token1)->getJson('/api/v1/shop/addresses');
    $listAddresses->assertOk();
    $addressesJson = json_encode($listAddresses->json(), JSON_THROW_ON_ERROR);
    expect($addressesJson)->toContain(sprintf('"id":%d', $address1->id))
        ->not->toContain(sprintf('"id":%d', $address2->id));

    $forbiddenOrder = $this->withToken($token1)->getJson('/api/v1/shop/orders/'.$order2->id);
    $forbiddenOrder->assertForbidden();

    $forbiddenAddress = $this->withToken($token1)->getJson('/api/v1/shop/addresses/'.$address2->id);
    $forbiddenAddress->assertForbidden();
});

