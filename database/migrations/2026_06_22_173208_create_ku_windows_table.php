<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ku_windows', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->enum('day_of_week', ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_active')->default(false);
            $table->boolean('triggered_manually')->default(false);
            $table->boolean('is_recurring')->default(true);
            $table->text('description')->nullable();
            $table->integer('estimated_participants')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ku_windows');
    }
};
