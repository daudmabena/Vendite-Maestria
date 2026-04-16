<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brand_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type', 64); // win_back | milestone | nurture | seasonal
            $table->string('channel', 64)->default('email'); // email | push | sms | all
            $table->string('status', 32)->default('draft'); // draft | active | paused | completed
            $table->json('trigger_rule')->nullable(); // flexible trigger conditions
            $table->json('audience_filter')->nullable(); // tier, last_seen_days, etc.
            $table->text('subject')->nullable();
            $table->text('body')->nullable();
            $table->unsignedInteger('sent_count')->default(0);
            $table->unsignedInteger('opened_count')->default(0);
            $table->unsignedInteger('converted_count')->default(0);
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('launched_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brand_campaigns');
    }
};
