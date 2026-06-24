<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notification_queue', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ku_window_id')->nullable()->constrained('ku_windows')->nullOnDelete();
            $table->string('sender');
            $table->string('recipient_group')->default('all'); // all, students, faculty
            $table->integer('recipient_count')->default(0);
            $table->enum('app_type', ['lms', 'email', 'social', 'system'])->default('lms');
            $table->text('message');
            $table->timestamp('held_at');
            $table->timestamp('released_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_queue');
    }
};
