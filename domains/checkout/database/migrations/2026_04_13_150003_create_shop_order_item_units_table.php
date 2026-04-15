<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_order_item_units', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_item_id')->constrained('shop_order_items')->cascadeOnDelete();
            $table->integer('adjustments_total')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_order_item_units');
    }
};
