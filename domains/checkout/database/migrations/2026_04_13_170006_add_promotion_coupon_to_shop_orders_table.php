<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shop_orders', function (Blueprint $table): void {
            $table->foreignId('promotion_coupon_id')->nullable()->after('billing_address_id')->constrained('shop_promotion_coupons')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('shop_orders', function (Blueprint $table): void {
            $table->dropForeign(['promotion_coupon_id']);
            $table->dropColumn('promotion_coupon_id');
        });
    }
};
