<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_shipment_units', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('shipment_id')->constrained('shop_shipments')->cascadeOnDelete();
            $table->foreignId('order_item_unit_id')->constrained('shop_order_item_units')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['shipment_id', 'order_item_unit_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_shipment_units');
    }
};
