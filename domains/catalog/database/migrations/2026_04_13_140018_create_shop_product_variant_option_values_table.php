<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_product_variant_option_values', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_variant_id')->constrained('shop_product_variants')->cascadeOnDelete();
            $table->foreignId('product_option_value_id')->constrained('shop_product_option_values')->cascadeOnDelete();
            $table->foreignId('product_option_id')->constrained('shop_product_options')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['product_variant_id', 'product_option_id'], 'shop_variant_one_value_per_option');
            $table->unique(['product_variant_id', 'product_option_value_id'], 'shop_variant_value_once');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_product_variant_option_values');
    }
};
