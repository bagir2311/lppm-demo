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
        // Menambahkan kolom is_reviewer pada tabel dosens yang sudah ada
        Schema::table('dosens', function (Blueprint $table) {
            $table->boolean('is_reviewer')->default(false)->after('kepakaran');
        });

        // Menambahkan kolom is_reviewer pada tabel users yang sudah ada
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_reviewer')->default(false)->after('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_reviewer');
        });

        Schema::table('dosens', function (Blueprint $table) {
            $table->dropColumn('is_reviewer');
        });
    }
};
