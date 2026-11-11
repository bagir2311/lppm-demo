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
        Schema::table('haki_submissions', function (Blueprint $table) {
            $table->string('file_ktp')->nullable()->change();
            $table->string('file_bukti_pembayaran')->nullable()->change();
            $table->string('file_surat_pengalihan')->nullable()->change();
            $table->string('file_surat_pernyataan')->nullable()->change();
            $table->string('file_lampiran_karya')->nullable()->change();
            if (!Schema::hasColumn('haki_submissions', 'data_pencipta')) {
                $table->json('data_pencipta')->nullable()->after('nama_anggota');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('haki_submissions', function (Blueprint $table) {
            if (Schema::hasColumn('haki_submissions', 'data_pencipta')) {
                $table->dropColumn('data_pencipta');
            }
        });
    }
};
