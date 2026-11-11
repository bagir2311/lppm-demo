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
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dosen_id');
            $table->unsignedBigInteger('periode_id');
            $table->string('judul');
            $table->enum('skema', ['Penelitian', 'Pengabdian']);
            $table->string('file_proposal');
            $table->string('file_rab');
            $table->enum('status', ['draft', 'submitted', 'in_review', 'approved', 'rejected'])->default('submitted');
            $table->integer('nilai_total')->nullable();
            $table->timestamps();

            $table->foreign('dosen_id')->references('id')->on('dosens')->onDelete('cascade');
            $table->foreign('periode_id')->references('id')->on('periodes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
