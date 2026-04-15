<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_tax_rates', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tax_category_id')->nullable()->constrained('shop_tax_categories')->nullOnDelete();
            $table->string('code')->unique();
            $table->string('name');
            $table->decimal('amount', 10, 6)->default(0);
            $table->boolean('included_in_price')->default(false);
            $table->string('calculator', 32)->default('default');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_tax_rates');
    }
};
