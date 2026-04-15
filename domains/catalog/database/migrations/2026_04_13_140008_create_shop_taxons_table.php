<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_taxons', function (Blueprint $table): void {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('parent_id')->nullable()->constrained('shop_taxons')->cascadeOnDelete();
            $table->unsignedInteger('position')->nullable();
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_taxons');
    }
};
