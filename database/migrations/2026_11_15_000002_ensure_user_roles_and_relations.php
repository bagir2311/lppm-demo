<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('dosen')->after('email');
            }
            if (!Schema::hasColumn('users', 'dosen_id')) {
                $table->unsignedBigInteger('dosen_id')->nullable()->after('role');
            }
        });

        Schema::table('dosens', function (Blueprint $table) {
            if (!Schema::hasColumn('dosens', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Safe to keep columns
    }
};
