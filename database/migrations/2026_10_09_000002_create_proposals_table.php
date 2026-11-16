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
        if (!Schema::hasTable('proposals')) {
            Schema::create('proposals', function (Blueprint $table) {
                $table->id();
                $table->string('judul');
                $table->foreignId('dosen_id')->nullable()->constrained('dosens')->nullOnDelete();
                $table->string('skema')->nullable();
                $table->string('tahun', 4)->nullable();
                $table->text('abstrak')->nullable();
                $table->string('file_proposal')->nullable();
                $table->string('status')->default('pending'); // pending, direview, diterima, ditolak, revisi
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
