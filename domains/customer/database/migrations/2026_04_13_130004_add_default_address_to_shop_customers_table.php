<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shop_customers', function (Blueprint $table): void {
            $table->foreignId('default_address_id')
                ->nullable()
                ->after('user_id')
                ->constrained('shop_addresses')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('shop_customers', function (Blueprint $table): void {
            $table->dropForeign(['default_address_id']);
            $table->dropColumn('default_address_id');
        });
    }
};
