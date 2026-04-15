<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_product_product_option', function (Blueprint $table): void {
            $table->foreignId('product_id')->constrained('shop_products')->cascadeOnDelete();
            $table->foreignId('product_option_id')->constrained('shop_product_options')->cascadeOnDelete();
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();

            $table->primary(['product_id', 'product_option_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_product_product_option');
    }
};
