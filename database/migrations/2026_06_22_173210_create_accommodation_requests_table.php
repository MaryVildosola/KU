<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('accommodation_requests', function (Blueprint $table) {
            $table->id();
            $table->string('student_ref_id'); // anonymous ref from mobile app
            $table->string('student_name')->nullable(); // optional display name
            $table->string('department')->nullable();
            $table->enum('category', ['Employment', 'Medical', 'Disability', 'Family', 'Other']);
            $table->text('notes');
            $table->text('modified_schedule')->nullable();
            $table->enum('status', ['pending', 'approved', 'denied', 'under_review'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accommodation_requests');
    }
};
