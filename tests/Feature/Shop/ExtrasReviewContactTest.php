<?php

declare(strict_types=1);

use Modules\Content\Models\ContactMessage;
use Modules\Customer\Models\Customer;
use Modules\Catalog\Models\Product;
use Modules\Catalog\Models\ProductReview;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('authenticated customer can submit product review and it is pending', function () {
    $user = User::factory()->create();
    $customer = Customer::query()->create([
        'user_id' => $user->id,
        'email' => $user->email,
    ]);
    Sanctum::actingAs($user);

    $product = Product::query()->create(['code' => 'reviewed-p', 'enabled' => true]);

    $response = $this->postJson('/api/v1/shop/product-reviews', [
        'product_id' => $product->id,
        'rating' => 5,
        'title' => 'Great product',
        'comment' => 'Loved it.',
    ]);

    $response->assertCreated()
        ->assertJsonPath('product_id', $product->id)
        ->assertJsonPath('customer_id', $customer->id)
        ->assertJsonPath('status', ProductReview::STATUS_PENDING);
});

test('public cannot submit product review without auth', function () {
    $product = Product::query()->create(['code' => 'review-auth', 'enabled' => true]);

    $response = $this->postJson('/api/v1/shop/product-reviews', [
        'product_id' => $product->id,
        'rating' => 4,
        'title' => 'Solid',
    ]);

    $response->assertUnauthorized();
});

test('public can submit contact message and status is new', function () {
    $response = $this->postJson('/api/v1/shop/contact-messages', [
        'email' => 'visitor@example.com',
        'name' => 'Visitor',
        'subject' => 'Question',
        'message' => 'Do you ship internationally?',
    ]);

    $response->assertCreated()
        ->assertJsonPath('email', 'visitor@example.com')
        ->assertJsonPath('status', ContactMessage::STATUS_NEW);
});

