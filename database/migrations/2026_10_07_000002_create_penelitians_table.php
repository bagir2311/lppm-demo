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
        Schema::create('penelitians', function (Blueprint $table) {
            $table->id();
            $table->text('judul');
            $table->text('abstrak')->nullable();
            $table->integer('tahun_pelaksanaan');
            $table->string('skema');
            $table->string('bidang_fokus');
            $table->foreignId('ketua_dosen_id')->constrained('dosens')->onDelete('cascade');
            $table->string('status')->default('Didanai');
            $table->string('file_pdf_url')->nullable();
            $table->bigInteger('dana_disetujui')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penelitians');
    }
};
