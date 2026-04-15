<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_promotion_coupons', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('promotion_id')->constrained('shop_promotions')->cascadeOnDelete();
            $table->string('code')->unique();
            $table->unsignedInteger('usage_limit')->nullable();
            $table->unsignedInteger('used')->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_promotion_coupons');
    }
};
