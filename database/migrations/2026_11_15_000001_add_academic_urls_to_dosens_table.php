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
        Schema::table('dosens', function (Blueprint $table) {
            if (!Schema::hasColumn('dosens', 'scholar_url')) {
                $table->string('scholar_url')->nullable()->after('avatar');
            }
            if (!Schema::hasColumn('dosens', 'sinta_url')) {
                $table->string('sinta_url')->nullable()->after('scholar_url');
            }
            if (!Schema::hasColumn('dosens', 'scopus_url')) {
                $table->string('scopus_url')->nullable()->after('sinta_url');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dosens', function (Blueprint $table) {
            if (Schema::hasColumn('dosens', 'scopus_url')) {
                $table->dropColumn('scopus_url');
            }
            if (Schema::hasColumn('dosens', 'sinta_url')) {
                $table->dropColumn('sinta_url');
            }
            if (Schema::hasColumn('dosens', 'scholar_url')) {
                $table->dropColumn('scholar_url');
            }
        });
    }
};
