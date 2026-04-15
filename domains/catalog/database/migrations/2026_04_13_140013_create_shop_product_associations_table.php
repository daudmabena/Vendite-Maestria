<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_product_associations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('owner_product_id')->constrained('shop_products')->cascadeOnDelete();
            $table->foreignId('product_association_type_id')->constrained('shop_product_association_types')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['owner_product_id', 'product_association_type_id'], 'product_assoc_owner_type_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_product_associations');
    }
};
