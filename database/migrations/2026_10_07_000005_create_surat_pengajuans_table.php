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
        Schema::create('surat_pengajuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('dosen_id')->nullable()->constrained('dosens')->onDelete('cascade');
            $table->string('jenis_surat'); // 'Surat Izin Penelitian', 'Pengantar Etik Penelitian'
            $table->string('nomor_surat')->nullable();
            $table->text('judul_penelitian');
            $table->string('lokasi_penelitian')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->string('status')->default('Pending'); // 'Pending', 'Disetujui', 'Ditolak'
            $table->text('catatan_admin')->nullable();
            $table->string('file_pdf_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_pengajuans');
    }
};
