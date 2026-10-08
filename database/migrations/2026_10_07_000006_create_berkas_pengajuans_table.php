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
        Schema::create('berkas_pengajuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('dosen_id')->nullable()->constrained('dosens')->onDelete('cascade');
            $table->foreignId('penelitian_id')->nullable()->constrained('penelitians')->onDelete('cascade');
            $table->foreignId('pengabdian_id')->nullable()->constrained('pengabdians')->onDelete('cascade');
            $table->string('jenis_berkas'); // 'Proposal', 'Laporan Kemajuan', 'Laporan Akhir', 'Dokumentasi Kegiatan', 'LPJ Keuangan'
            $table->string('nama_file');
            $table->string('file_path');
            $table->string('ukuran_file')->nullable();
            $table->string('status')->default('Submitted'); // 'Submitted', 'Revisi', 'Disetujui'
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berkas_pengajuans');
    }
};
