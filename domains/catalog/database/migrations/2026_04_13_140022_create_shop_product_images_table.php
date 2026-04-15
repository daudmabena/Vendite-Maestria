<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_product_images', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained('shop_products')->cascadeOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained('shop_product_variants')->nullOnDelete();
            $table->string('path');
            $table->unsignedInteger('position')->default(0);
            $table->string('mime_type')->nullable();
            $table->string('original_name')->nullable();
            $table->timestamps();

            $table->index(['product_id', 'position']);
            $table->index(['product_variant_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_product_images');
    }
};
