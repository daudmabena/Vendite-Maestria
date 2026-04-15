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
            $table->unsignedInteger('on_hand')->default(0)->after('enabled');
            $table->unsignedInteger('on_hold')->default(0)->after('on_hand');
            $table->boolean('tracked')->default(false)->after('on_hold');
        });
    }

    public function down(): void
    {
        Schema::table('shop_product_variants', function (Blueprint $table): void {
            $table->dropColumn(['on_hand', 'on_hold', 'tracked']);
        });
    }
};
