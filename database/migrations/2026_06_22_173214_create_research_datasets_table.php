<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('research_datasets', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('dataset_period'); // e.g. "Sem 1 AY 2025-2026"
            $table->enum('access_level', ['public', 'approved_only'])->default('approved_only');
            $table->json('data_json'); // anonymized aggregate data
            $table->string('department_scope')->nullable(); // null = institution-wide
            $table->integer('sample_size')->default(0);
            $table->text('description')->nullable();
            $table->string('file_path')->nullable(); // CSV export path
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('research_datasets');
    }
};
