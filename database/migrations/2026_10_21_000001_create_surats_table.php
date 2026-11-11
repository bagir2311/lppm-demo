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
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->nullable();
            $table->string('jenis_surat');
            $table->string('nama_dosen');
            $table->string('nidn')->nullable();
            $table->string('prodi');
            $table->text('keperluan');
            $table->string('instansi_tujuan');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('qr_code_hash')->nullable()->index();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};
