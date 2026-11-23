<?php

namespace App\Http\Controllers;

use App\Models\Nilai;

class DashboardController extends Controller
{
    public function index()
    {
        $jumlahMK = Nilai::where('jenis_data', 'mata_kuliah')->count();
        $jumlahNilai = Nilai::where('jenis_data', 'nilai')->count();
        $jumlahTugas = Nilai::where('jenis_data', 'tugas')->count();

        $rataRata = Nilai::where('jenis_data', 'nilai')
            ->avg('nilai_akhir') ?? 0;

        $tugasSelesai = Nilai::where('jenis_data', 'tugas')
            ->where('status_tugas', 'Selesai')
            ->count();

        $tugasBelum = Nilai::where('jenis_data', 'tugas')
            ->where('status_tugas', 'Belum Dikerjakan')
            ->count();

        $tugasTerbaru = Nilai::where('jenis_data', 'tugas')
            ->orderBy('deadline')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'jumlahMK',
            'jumlahNilai',
            'jumlahTugas',
            'rataRata',
            'tugasSelesai',
            'tugasBelum',
            'tugasTerbaru'
        ));
    }
}
