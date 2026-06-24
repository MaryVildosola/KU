<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fomo_risk_scores', function (Blueprint $table) {
            $table->id();
            $table->decimal('composite_score', 5, 2); // 0–100
            $table->decimal('notification_pressure', 5, 2); // late-night notification rate
            $table->decimal('deadline_clustering', 5, 2); // % deadlines between 10PM–2AM
            $table->decimal('midnight_activity', 5, 2); // LMS activity after midnight
            $table->decimal('after_hours_faculty', 5, 2); // faculty messages after 9PM
            $table->string('risk_level')->default('moderate'); // low, moderate, high, critical
            $table->integer('total_users_analyzed')->default(0);
            $table->timestamp('computed_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fomo_risk_scores');
    }
};
