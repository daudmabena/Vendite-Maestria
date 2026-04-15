<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('non-admin user cannot create product via admin-protected endpoint', function () {
    $user = User::factory()->create(['is_admin' => false]);
    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/shop/products', [
        'code' => 'non-admin-product',
        'enabled' => true,
        'variant_selection_method' => 'choice',
    ]);

    $response->assertForbidden();
});

test('admin user can create product via admin-protected endpoint', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    Sanctum::actingAs($admin);

    $response = $this->postJson('/api/v1/shop/products', [
        'code' => 'admin-product',
        'enabled' => true,
        'variant_selection_method' => 'choice',
    ]);

    $response->assertCreated()
        ->assertJsonPath('code', 'admin-product');
});

