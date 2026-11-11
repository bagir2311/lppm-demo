<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dosens', function (Blueprint $table) {
            if (!Schema::hasColumn('dosens', 'status')) {
                $table->string('status')->default('published')->after('avatar');
            }
            if (!Schema::hasColumn('dosens', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('dosens', function (Blueprint $table) {
            if (Schema::hasColumn('dosens', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('dosens', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};
