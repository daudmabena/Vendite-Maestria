<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_product_attribute_values', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained('shop_products')->cascadeOnDelete();
            $table->foreignId('product_attribute_id')->constrained('shop_product_attributes')->cascadeOnDelete();
            $table->string('locale', 12)->default('');
            $table->text('text_value')->nullable();
            $table->bigInteger('integer_value')->nullable();
            $table->decimal('float_value', 12, 6)->nullable();
            $table->boolean('boolean_value')->nullable();
            $table->json('json_value')->nullable();
            $table->timestamps();

            $table->unique(['product_id', 'product_attribute_id', 'locale'], 'shop_product_attr_locale_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_product_attribute_values');
    }
};
