<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('window_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ku_window_id')->constrained('ku_windows')->cascadeOnDelete();
            $table->integer('participants')->default(0);
            $table->integer('notifications_held')->default(0);
            $table->integer('notifications_released')->default(0);
            $table->decimal('avg_cri_improvement', 5, 2)->nullable(); // from mobile app aggregate
            $table->decimal('faculty_participation_rate', 5, 2)->default(0);
            $table->decimal('student_participation_rate', 5, 2)->default(0);
            $table->integer('emergency_bypasses')->default(0);
            $table->timestamp('window_started_at');
            $table->timestamp('window_ended_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('window_analytics');
    }
};
