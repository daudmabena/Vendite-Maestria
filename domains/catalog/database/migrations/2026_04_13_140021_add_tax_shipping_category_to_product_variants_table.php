<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shop_product_variants', function (Blueprint $table): void {
            $table->foreignId('tax_category_id')->nullable()->after('tracked')->constrained('shop_tax_categories')->nullOnDelete();
            $table->foreignId('shipping_category_id')->nullable()->after('tax_category_id')->constrained('shop_shipping_categories')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('shop_product_variants', function (Blueprint $table): void {
            $table->dropForeign(['tax_category_id']);
            $table->dropForeign(['shipping_category_id']);
            $table->dropColumn(['tax_category_id', 'shipping_category_id']);
        });
    }
};
