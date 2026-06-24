<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'faculty', 'researcher'])->default('faculty')->after('email');
            $table->string('department')->nullable()->after('role');
            $table->string('position')->nullable()->after('department');
            $table->string('avatar_initials', 3)->nullable()->after('position');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'department', 'position', 'avatar_initials']);
        });
    }
};
