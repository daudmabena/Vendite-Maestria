<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brand_customer_engagements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->unique()->index();
            $table->unsignedSmallInteger('familiarity_score')->default(0); // 0–100
            $table->unsignedInteger('total_touchpoints')->default(0);
            $table->string('trust_tier', 32)->default('cold'); // cold | warming | familiar | loyal
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brand_customer_engagements');
    }
};
