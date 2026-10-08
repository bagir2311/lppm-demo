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
        Schema::create('penilaian_reviewers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('penelitian_id')->nullable()->constrained('penelitians')->onDelete('cascade');
            $table->foreignId('pengabdian_id')->nullable()->constrained('pengabdians')->onDelete('cascade');
            $table->integer('skor_metodologi')->default(0);
            $table->integer('skor_relevansi')->default(0);
            $table->integer('skor_kelayakan')->default(0);
            $table->integer('total_skor')->default(0);
            $table->string('rekomendasi')->nullable(); // 'Diterima', 'Revisi', 'Ditolak'
            $table->text('catatan_reviewer')->nullable();
            $table->string('status')->default('Belum Dinilai'); // 'Belum Dinilai', 'Selesai'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian_reviewers');
    }
};
