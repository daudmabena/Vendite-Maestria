<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_contact_messages', function (Blueprint $table): void {
            $table->id();
            $table->string('email');
            $table->string('name')->nullable();
            $table->string('subject')->nullable();
            $table->text('message');
            $table->string('status', 16)->default('new');
            $table->timestamp('resolved_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_contact_messages');
    }
};
