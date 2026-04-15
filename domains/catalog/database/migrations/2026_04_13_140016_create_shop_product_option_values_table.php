<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_product_option_values', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_option_id')->constrained('shop_product_options')->cascadeOnDelete();
            $table->string('code');
            $table->string('value');
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();

            $table->unique(['product_option_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_product_option_values');
    }
};
