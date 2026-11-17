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
        if (! Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('dosen')->after('email'); // 'admin_lppm', 'dosen', 'reviewer'
            });
        }

        if (! Schema::hasColumn('users', 'dosen_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('dosen_id')->nullable()->after('role')->constrained('dosens')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['dosen_id']);
            $table->dropColumn(['role', 'dosen_id']);
        });
    }
};
