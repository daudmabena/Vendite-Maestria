<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_order_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_id')->constrained('shop_orders')->cascadeOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained('shop_product_variants')->nullOnDelete();
            $table->string('product_name')->nullable();
            $table->string('variant_name')->nullable();
            $table->unsignedInteger('version')->default(1);
            $table->unsignedInteger('quantity')->default(0);
            $table->integer('unit_price')->default(0);
            $table->integer('original_unit_price')->nullable();
            $table->integer('units_total')->default(0);
            $table->integer('adjustments_total')->default(0);
            $table->integer('total')->default(0);
            $table->boolean('immutable')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_order_items');
    }
};
