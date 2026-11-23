<?php
namespace App\Http\Controllers;

use App\Models\Nilai;
use Illuminate\Support\Facades\DB;

class GrafikController extends Controller
{
    public function index()
    {
        $queryNilai = Nilai::where('jenis_data', 'nilai');

        $totalNilai = (clone $queryNilai)->count();

        $rataRata = (clone $queryNilai)
            ->whereNotNull('nilai_akhir')
            ->avg('nilai_akhir') ?? 0;

        $nilaiTertinggi = (clone $queryNilai)
            ->max('nilai_akhir') ?? 0;

        $nilaiTerendah = (clone $queryNilai)
            ->whereNotNull('nilai_akhir')
            ->min('nilai_akhir') ?? 0;

        // Rata-rata per mata kuliah
        $dataMataKuliah = (clone $queryNilai)
            ->select(
                'kode_mk',
                'nama_mk',
                DB::raw('AVG(nilai_akhir) as rata_rata')
            )
            ->whereNotNull('nilai_akhir')
            ->groupBy('kode_mk', 'nama_mk')
            ->orderBy('nama_mk')
            ->get();

        $labelMataKuliah = $dataMataKuliah
            ->pluck('kode_mk')
            ->values();

        $nilaiMataKuliah = $dataMataKuliah
            ->pluck('rata_rata')
            ->map(fn($nilai) => round((float) $nilai, 2))
            ->values();

        // Distribusi predikat
        $distribusi = (clone $queryNilai)
            ->select('nilai_huruf', DB::raw('COUNT(*) as jumlah'))
            ->whereNotNull('nilai_huruf')
            ->groupBy('nilai_huruf')
            ->pluck('jumlah', 'nilai_huruf');

        $labelPredikat = ['A', 'B', 'C', 'D', 'E'];

        $jumlahPredikat = collect($labelPredikat)
            ->map(fn($huruf) => (int) ($distribusi[$huruf] ?? 0))
            ->values();

        // Perkembangan nilai per bulan
        $perkembangan = (clone $queryNilai)
            ->select(
                DB::raw("DATE_FORMAT(tanggal_penilaian, '%Y-%m') as periode"),
                DB::raw('AVG(nilai_akhir) as rata_rata')
            )
            ->whereNotNull('tanggal_penilaian')
            ->whereNotNull('nilai_akhir')
            ->groupBy('periode')
            ->orderBy('periode')
            ->get();

        $labelPeriode = $perkembangan
            ->pluck('periode')
            ->values();

        $nilaiPeriode = $perkembangan
            ->pluck('rata_rata')
            ->map(fn($nilai) => round((float) $nilai, 2))
            ->values();

        return view('grafik.index', compact(
            'totalNilai',
            'rataRata',
            'nilaiTertinggi',
            'nilaiTerendah',
            'labelMataKuliah',
            'nilaiMataKuliah',
            'labelPredikat',
            'jumlahPredikat',
            'labelPeriode',
            'nilaiPeriode'
        ));
    }
}
