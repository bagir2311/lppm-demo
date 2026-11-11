<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Buat tabel haki_submissions
        if (!Schema::hasTable('haki_submissions')) {
            Schema::create('haki_submissions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('dosen_id')->nullable()->constrained('dosens')->nullOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('judul_ciptaan');
                $table->string('jenis_ciptaan')->nullable(); // misal: Program Komputer, Buku, Karya Tulis, Paten, Desain Industri
                $table->string('nama_ketua');
                $table->text('nama_anggota')->nullable();
                $table->string('file_ktp');
                $table->string('file_surat_pernyataan');
                $table->string('file_surat_pengalihan');
                $table->string('file_lampiran_karya');
                $table->string('file_bukti_pembayaran');
                $table->string('status')->default('Diajukan'); // 'Diajukan', 'Pemeriksaan', 'Disetujui', 'Ditolak'
                $table->text('catatan_admin')->nullable();
                $table->string('nomor_pencatatan')->nullable();
                $table->string('file_sertifikat')->nullable();
                $table->timestamps();
            });
        }

        // 2. Buat tabel laporans (LPJ Pelaporan Kemajuan / Akhir)
        if (!Schema::hasTable('laporans')) {
            Schema::create('laporans', function (Blueprint $table) {
                $table->id();
                $table->foreignId('proposal_id')->nullable()->constrained('proposals')->cascadeOnDelete();
                $table->foreignId('dosen_id')->nullable()->constrained('dosens')->nullOnDelete();
                $table->enum('fase_pelaporan', ['70%', '100%']);
                $table->string('file_laporan_substansi');
                $table->string('file_laporan_keuangan');
                $table->string('file_dokumentasi');
                $table->enum('pilihan_luaran', ['Artikel', 'Berita di Koran', 'HKI', 'Video YouTube'])->nullable();
                $table->text('url_luaran')->nullable();
                $table->string('file_luaran')->nullable();
                $table->string('status')->default('Submitted'); // Submitted, Disetujui, Revisi, Ditolak
                $table->text('catatan')->nullable();
                $table->timestamps();
            });
        }

        // 3. Pastikan kolom proposals terupdate
        Schema::table('proposals', function (Blueprint $table) {
            if (!Schema::hasColumn('proposals', 'jenis_hibah')) {
                $table->string('jenis_hibah')->nullable()->after('skema');
            }
            if (!Schema::hasColumn('proposals', 'sub_kategori')) {
                $table->string('sub_kategori')->nullable()->after('jenis_hibah');
            }
        });

        // 4. Pastikan status proposal sesuai alur: Diajukan -> Diterima -> Di-review -> Didanai / Tidak Didanai
        DB::statement("ALTER TABLE proposals MODIFY status ENUM('Diajukan', 'Diterima', 'Di-review', 'Didanai', 'Tidak Didanai', 'draft', 'submitted', 'in_review', 'approved', 'rejected') DEFAULT 'Diajukan'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporans');
        Schema::dropIfExists('haki_submissions');
    }
};
