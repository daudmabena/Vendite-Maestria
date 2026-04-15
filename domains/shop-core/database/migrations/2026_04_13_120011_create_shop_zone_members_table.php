<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_zone_members', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('zone_id')->constrained('shop_zones')->cascadeOnDelete();
            $table->string('code');
            $table->timestamps();

            $table->unique(['zone_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_zone_members');
    }
};
