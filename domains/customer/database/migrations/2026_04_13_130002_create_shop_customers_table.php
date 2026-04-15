<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_customers', function (Blueprint $table): void {
            $table->id();
            $table->string('email')->nullable();
            $table->string('email_canonical')->nullable()->unique();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->date('birthday')->nullable();
            $table->string('gender', 1)->default('u');
            $table->foreignId('customer_group_id')->nullable()->constrained('shop_customer_groups')->nullOnDelete();
            $table->string('phone_number')->nullable();
            $table->boolean('subscribed_to_newsletter')->default(false);
            $table->foreignId('user_id')->nullable()->unique()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_customers');
    }
};
