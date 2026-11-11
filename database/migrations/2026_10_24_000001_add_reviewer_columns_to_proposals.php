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
        Schema::table('proposals', function (Blueprint $table) {
            $table->unsignedBigInteger('reviewer_id')->nullable()->after('dosen_id');
            $table->text('catatan_reviewer')->nullable()->after('nilai_total');
            $table->enum('rekomendasi', ['Diterima', 'Ditolak', 'Revisi'])->nullable()->after('catatan_reviewer');

            $table->foreign('reviewer_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            $table->dropForeign(['reviewer_id']);
            $table->dropColumn(['reviewer_id', 'catatan_reviewer', 'rekomendasi']);
        });
    }
};
