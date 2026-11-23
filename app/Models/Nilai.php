<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    protected $table = 'nilais';

    protected $fillable = [
        'jenis_data',
        'nim',
        'nama_mahasiswa',
        'kode_mk',
        'nama_mk',
        'sks',
        'semester',
        'dosen_pengampu',
        'nilai_tugas',
        'nilai_uts',
        'nilai_uas',
        'nilai_praktikum',
        'nilai_akhir',
        'nilai_huruf',
        'tanggal_penilaian',
        'judul_tugas',
        'deskripsi_tugas',
        'deadline',
        'status_tugas',
    ];

    protected function casts(): array
    {
        return [
            'nilai_tugas' => 'decimal:2',
            'nilai_uts' => 'decimal:2',
            'nilai_uas' => 'decimal:2',
            'nilai_praktikum' => 'decimal:2',
            'nilai_akhir' => 'decimal:2',
            'tanggal_penilaian' => 'date',
            'deadline' => 'date',
        ];
    }
}
