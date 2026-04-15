<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_product_association_product', function (Blueprint $table): void {
            $table->foreignId('product_association_id')->constrained('shop_product_associations')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('shop_products')->cascadeOnDelete();
            $table->primary(['product_association_id', 'product_id'], 'assoc_product_primary');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_product_association_product');
    }
};
