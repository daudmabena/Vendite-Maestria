<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_product_variant_translations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_variant_id')->constrained('shop_product_variants')->cascadeOnDelete();
            $table->string('locale', 12);
            $table->string('name')->nullable();
            $table->timestamps();

            $table->unique(['product_variant_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_product_variant_translations');
    }
};
