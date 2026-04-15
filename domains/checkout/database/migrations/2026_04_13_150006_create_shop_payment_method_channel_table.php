<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_payment_method_channel', function (Blueprint $table): void {
            $table->foreignId('payment_method_id')->constrained('shop_payment_methods')->cascadeOnDelete();
            $table->foreignId('channel_id')->constrained('shop_channels')->cascadeOnDelete();
            $table->primary(['payment_method_id', 'channel_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_payment_method_channel');
    }
};
