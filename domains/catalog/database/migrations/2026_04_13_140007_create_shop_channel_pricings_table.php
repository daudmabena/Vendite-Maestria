<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_channel_pricings', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_variant_id')->constrained('shop_product_variants')->cascadeOnDelete();
            $table->foreignId('channel_id')->constrained('shop_channels')->cascadeOnDelete();
            $table->integer('price')->nullable();
            $table->integer('original_price')->nullable();
            $table->integer('minimum_price')->default(0);
            $table->integer('lowest_price_before_discount')->nullable();
            $table->timestamps();

            $table->unique(['product_variant_id', 'channel_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_channel_pricings');
    }
};
