<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_product_variants', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained('shop_products')->cascadeOnDelete();
            $table->string('code');
            $table->unsignedInteger('position')->nullable();
            $table->boolean('enabled')->default(true);
            $table->timestamps();

            $table->unique(['product_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_product_variants');
    }
};
