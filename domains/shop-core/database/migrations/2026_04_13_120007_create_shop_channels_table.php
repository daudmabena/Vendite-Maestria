<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_channels', function (Blueprint $table): void {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('hostname')->nullable();
            $table->string('color', 32)->nullable();
            $table->foreignId('base_currency_id')->nullable()->constrained('shop_currencies')->nullOnDelete();
            $table->foreignId('default_locale_id')->nullable()->constrained('shop_locales')->nullOnDelete();
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_channels');
    }
};
