<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_channel_country', function (Blueprint $table): void {
            $table->foreignId('channel_id')->constrained('shop_channels')->cascadeOnDelete();
            $table->foreignId('country_id')->constrained('shop_countries')->cascadeOnDelete();
            $table->primary(['channel_id', 'country_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_channel_country');
    }
};
