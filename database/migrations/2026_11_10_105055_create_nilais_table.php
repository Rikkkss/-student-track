<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nilais', function (Blueprint $table) {
            $table->id();

            // Jenis data
            $table->enum('jenis_data', [
                'mata_kuliah',
                'nilai',
                'tugas'
            ]);

            // Identitas mahasiswa
            $table->string('nim')->nullable();
            $table->string('nama_mahasiswa')->nullable();

            // Informasi mata kuliah
            $table->string('kode_mk')->nullable();
            $table->string('nama_mk')->nullable();
            $table->unsignedTinyInteger('sks')->nullable();
            $table->string('semester')->nullable();
            $table->string('dosen_pengampu')->nullable();

            // Informasi nilai
            $table->decimal('nilai_tugas', 5, 2)->nullable();
            $table->decimal('nilai_uts', 5, 2)->nullable();
            $table->decimal('nilai_uas', 5, 2)->nullable();
            $table->decimal('nilai_praktikum', 5, 2)->nullable();
            $table->decimal('nilai_akhir', 5, 2)->nullable();
            $table->string('nilai_huruf', 2)->nullable();
            $table->date('tanggal_penilaian')->nullable();

            // Informasi tugas
            $table->string('judul_tugas')->nullable();
            $table->text('deskripsi_tugas')->nullable();
            $table->date('deadline')->nullable();
            $table->enum('status_tugas', [
                'Belum Dikerjakan',
                'Sedang Dikerjakan',
                'Selesai'
            ])->nullable();

            $table->timestamps();

            // Index untuk mempercepat filter
            $table->index('jenis_data');
            $table->index('tanggal_penilaian');
            $table->index('deadline');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilais');
    }
};
