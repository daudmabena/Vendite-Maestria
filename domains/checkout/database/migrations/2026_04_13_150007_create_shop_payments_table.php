<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_payments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_id')->constrained('shop_orders')->cascadeOnDelete();
            $table->foreignId('payment_method_id')->nullable()->constrained('shop_payment_methods')->nullOnDelete();
            $table->string('currency_code', 3);
            $table->integer('amount')->default(0);
            $table->string('state', 20)->default('cart');
            $table->json('details')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_payments');
    }
};
