<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Nilai;
use Carbon\Carbon;

class NilaiSeeder extends Seeder
{
    public function run(): void
    {
        $mataKuliah = [
            ['IF101', 'Pemrograman Web', 3, 'Pemrograman'],
            ['IF102', 'Basis Data', 3, 'Basis Data'],
            ['IF103', 'Jaringan Komputer', 3, 'Jaringan'],
            ['IF104', 'Sistem Operasi', 3, 'Sistem Operasi'],
            ['IF105', 'Algoritma dan Struktur Data', 3, 'Algoritma'],
            ['IF106', 'Rekayasa Perangkat Lunak', 3, 'RPL'],
            ['IF107', 'Keamanan Jaringan', 2, 'Keamanan'],
            ['IF108', 'Pemrograman Berorientasi Objek', 3, 'PBO'],
            ['IF109', 'Interaksi Manusia dan Komputer', 2, 'IMK'],
            ['IF110', 'Manajemen Proyek TI', 2, 'Manajemen'],
        ];

        // 10 data mata kuliah
        foreach ($mataKuliah as $mk) {
            Nilai::create([
                'jenis_data' => 'mata_kuliah',
                'nim' => '240001',
                'nama_mahasiswa' => 'Mahasiswa Dummy',
                'kode_mk' => $mk[0],
                'nama_mk' => $mk[1],
                'sks' => $mk[2],
                'semester' => '3',
                'dosen_pengampu' => 'Dosen ' . $mk[3],
            ]);
        }

        // 50 data nilai
        for ($i = 1; $i <= 50; $i++) {
            $mk = $mataKuliah[($i - 1) % count($mataKuliah)];

            $tugas = rand(60, 100);
            $uts = rand(55, 100);
            $uas = rand(55, 100);
            $praktikum = rand(60, 100);

            $akhir = round(
                ($tugas * 0.20) +
                ($uts * 0.30) +
                ($uas * 0.30) +
                ($praktikum * 0.20),
                2
            );

            if ($akhir >= 85) {
                $huruf = 'A';
            } elseif ($akhir >= 75) {
                $huruf = 'B';
            } elseif ($akhir >= 65) {
                $huruf = 'C';
            } elseif ($akhir >= 50) {
                $huruf = 'D';
            } else {
                $huruf = 'E';
            }

            Nilai::create([
                'jenis_data' => 'nilai',
                'nim' => '240001',
                'nama_mahasiswa' => 'Mahasiswa Dummy',
                'kode_mk' => $mk[0],
                'nama_mk' => $mk[1],
                'sks' => $mk[2],
                'semester' => '3',
                'nilai_tugas' => $tugas,
                'nilai_uts' => $uts,
                'nilai_uas' => $uas,
                'nilai_praktikum' => $praktikum,
                'nilai_akhir' => $akhir,
                'nilai_huruf' => $huruf,
                'tanggal_penilaian' => Carbon::now()
                    ->subDays(rand(1, 120))
                    ->toDateString(),
            ]);
        }

        // 50 data tugas
        $judulTugas = [
            'Membuat laporan praktikum',
            'Membuat aplikasi sederhana',
            'Menganalisis studi kasus',
            'Membuat desain database',
            'Konfigurasi jaringan komputer',
            'Membuat dokumentasi proyek',
            'Membuat presentasi kelompok',
            'Mengerjakan latihan pemrograman',
            'Membuat laporan penelitian',
            'Menganalisis sistem informasi',
        ];

        for ($i = 1; $i <= 50; $i++) {
            $mk = $mataKuliah[($i - 1) % count($mataKuliah)];

            $status = [
                'Belum Dikerjakan',
                'Sedang Dikerjakan',
                'Selesai',
            ][($i - 1) % 3];

            Nilai::create([
                'jenis_data' => 'tugas',
                'nim' => '240001',
                'nama_mahasiswa' => 'Mahasiswa Dummy',
                'kode_mk' => $mk[0],
                'nama_mk' => $mk[1],
                'judul_tugas' => $judulTugas[($i - 1) % 10] . ' ' . $i,
                'deskripsi_tugas' => 'Deskripsi tugas dummy nomor ' . $i,
                'deadline' => Carbon::now()
                    ->addDays(rand(-20, 30))
                    ->toDateString(),
                'status_tugas' => $status,
            ]);
        }
    }
}
