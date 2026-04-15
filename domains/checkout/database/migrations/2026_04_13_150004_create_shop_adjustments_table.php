<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_adjustments', function (Blueprint $table): void {
            $table->id();
            $table->string('adjustable_type');
            $table->unsignedBigInteger('adjustable_id');
            $table->string('type');
            $table->string('label')->nullable();
            $table->integer('amount')->default(0);
            $table->boolean('neutral')->default(false);
            $table->boolean('locked')->default(false);
            $table->string('origin_code')->nullable();
            $table->json('details')->nullable();
            $table->timestamps();

            $table->index(['adjustable_type', 'adjustable_id']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_adjustments');
    }
};
