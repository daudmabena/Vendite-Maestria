<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_orders', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('shop_customers')->nullOnDelete();
            $table->foreignId('channel_id')->nullable()->constrained('shop_channels')->nullOnDelete();
            $table->foreignId('shipping_address_id')->nullable()->constrained('shop_addresses')->nullOnDelete();
            $table->foreignId('billing_address_id')->nullable()->constrained('shop_addresses')->nullOnDelete();
            $table->string('number')->nullable()->unique();
            $table->string('token_value')->nullable()->unique();
            $table->string('currency_code', 3)->nullable();
            $table->string('locale_code', 12)->nullable();
            $table->string('state', 20)->default('cart');
            $table->text('notes')->nullable();
            $table->timestamp('checkout_completed_at')->nullable();
            $table->integer('items_total')->default(0);
            $table->integer('adjustments_total')->default(0);
            $table->integer('total')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_orders');
    }
};
