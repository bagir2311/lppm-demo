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
        // 1. Buat tabel hkis jika belum ada
        if (!Schema::hasTable('hkis')) {
            Schema::create('hkis', function (Blueprint $table) {
                $table->id();
                $table->foreignId('dosen_id')->nullable()->constrained('dosens')->nullOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('judul_ciptaan');
                $table->string('jenis_ciptaan')->nullable(); // misal: Program Komputer, Buku, Karya Tulis, Paten
                $table->string('nomor_permohonan')->nullable();
                $table->string('nomor_pencatatan')->nullable();
                $table->string('status')->default('Diajukan'); // 'Diajukan', 'Pemeriksaan', 'Disetujui', 'Ditolak'
                $table->string('file_dokumen')->nullable();
                $table->string('file_sertifikat')->nullable();
                $table->text('keterangan')->nullable();
                $table->timestamps();
            });
        }

        // 2. Update tabel proposals: jenis_hibah, sub_kategori, dan update enum status
        Schema::table('proposals', function (Blueprint $table) {
            if (!Schema::hasColumn('proposals', 'jenis_hibah')) {
                $table->string('jenis_hibah')->nullable()->after('skema'); // 'Internal LPPM' atau 'Eksternal (BIMA Dikti)'
            }
            if (!Schema::hasColumn('proposals', 'sub_kategori')) {
                $table->string('sub_kategori')->nullable()->after('jenis_hibah'); // 'Dasar' atau 'Terapan'
            }
        });

        // Update enum status pada proposals
        // Di MySQL kita bisa jalankan ALTER TABLE MODIFY status
        DB::statement("ALTER TABLE proposals MODIFY status ENUM('Diajukan', 'Diterima', 'Di-review', 'Didanai', 'Tidak Didanai', 'draft', 'submitted', 'in_review', 'approved', 'rejected') DEFAULT 'Diajukan'");

        // 3. Update tabel berkas_pengajuans: fase_pelaporan, pilihan_luaran
        Schema::table('berkas_pengajuans', function (Blueprint $table) {
            if (!Schema::hasColumn('berkas_pengajuans', 'fase_pelaporan')) {
                $table->string('fase_pelaporan')->nullable()->after('jenis_berkas'); // '70%' atau '100%'
            }
            if (!Schema::hasColumn('berkas_pengajuans', 'pilihan_luaran')) {
                $table->string('pilihan_luaran')->nullable()->after('fase_pelaporan'); // 'Artikel', 'Berita Koran', 'HKI', 'Video YouTube'
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('berkas_pengajuans', function (Blueprint $table) {
            if (Schema::hasColumn('berkas_pengajuans', 'pilihan_luaran')) {
                $table->dropColumn('pilihan_luaran');
            }
            if (Schema::hasColumn('berkas_pengajuans', 'fase_pelaporan')) {
                $table->dropColumn('fase_pelaporan');
            }
        });

        DB::statement("ALTER TABLE proposals MODIFY status ENUM('draft', 'submitted', 'in_review', 'approved', 'rejected') DEFAULT 'submitted'");

        Schema::table('proposals', function (Blueprint $table) {
            if (Schema::hasColumn('proposals', 'sub_kategori')) {
                $table->dropColumn('sub_kategori');
            }
            if (Schema::hasColumn('proposals', 'jenis_hibah')) {
                $table->dropColumn('jenis_hibah');
            }
        });

        Schema::dropIfExists('hkis');
    }
};
