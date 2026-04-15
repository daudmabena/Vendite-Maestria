<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_channel_currency', function (Blueprint $table): void {
            $table->foreignId('channel_id')->constrained('shop_channels')->cascadeOnDelete();
            $table->foreignId('currency_id')->constrained('shop_currencies')->cascadeOnDelete();
            $table->primary(['channel_id', 'currency_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_channel_currency');
    }
};
