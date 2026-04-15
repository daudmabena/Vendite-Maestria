<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_shipping_methods', function (Blueprint $table): void {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('shipping_category_id')->nullable()->constrained('shop_shipping_categories')->nullOnDelete();
            $table->foreignId('zone_id')->nullable()->constrained('shop_zones')->nullOnDelete();
            $table->string('calculator', 32)->default('flat_rate');
            $table->json('configuration')->nullable();
            $table->unsignedInteger('position')->nullable();
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_shipping_methods');
    }
};
