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
            $table->string('bidang_garap')->nullable()->after('nama')->comment('Fokus riset atau kepakaran');
            $table->string('kontak_kolaborasi')->nullable()->after('bidang_garap')->comment('Email atau No HP untuk kolaborasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dosens', function (Blueprint $table) {
            $table->dropColumn(['bidang_garap', 'kontak_kolaborasi']);
        });
    }
};