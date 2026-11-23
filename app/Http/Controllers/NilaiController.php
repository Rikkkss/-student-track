<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function index(Request $request)
    {
        $query = Nilai::where('jenis_data', 'nilai');

        if ($request->filled('sort')) {
            if ($request->sort === 'tanggal') {
                $query->orderBy('tanggal_penilaian', 'desc');
            } elseif ($request->sort === 'tertinggi') {
                $query->orderBy('nilai_akhir', 'desc');
            } elseif ($request->sort === 'terendah') {
                $query->orderBy('nilai_akhir', 'asc');
            }
        } else {
            $query->latest();
        }

        $nilai = $query->paginate(10)->withQueryString();

        return view('nilai.index', compact('nilai'));
    }

    public function create()
    {
        $mataKuliah = Nilai::where('jenis_data', 'mata_kuliah')
            ->orderBy('nama_mk')
            ->get();

        return view('nilai.create', compact('mataKuliah'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode_mk' => 'required|string|max:30',
            'nama_mk' => 'required|string|max:255',
            'nilai_tugas' => 'required|numeric|min:0|max:100',
            'nilai_uts' => 'required|numeric|min:0|max:100',
            'nilai_uas' => 'required|numeric|min:0|max:100',
            'nilai_praktikum' => 'required|numeric|min:0|max:100',
            'tanggal_penilaian' => 'required|date',
        ]);

        $data['nilai_akhir'] = round(
            ($data['nilai_tugas'] * 0.20) +
            ($data['nilai_uts'] * 0.30) +
            ($data['nilai_uas'] * 0.30) +
            ($data['nilai_praktikum'] * 0.20),
            2
        );

        $data['nilai_huruf'] = match (true) {
            $data['nilai_akhir'] >= 85 => 'A',
            $data['nilai_akhir'] >= 75 => 'B',
            $data['nilai_akhir'] >= 65 => 'C',
            $data['nilai_akhir'] >= 50 => 'D',
            default => 'E',
        };

        $data['jenis_data'] = 'nilai';
        $data['nim'] = '240001';
        $data['nama_mahasiswa'] = 'Mahasiswa Dummy';

        $mataKuliah = Nilai::where('jenis_data', 'mata_kuliah')
            ->where('kode_mk', $data['kode_mk'])
            ->firstOrFail();

        $data['sks'] = $mataKuliah->sks;
        $data['semester'] = $mataKuliah->semester;

        Nilai::create($data);

        return redirect()->route('nilai.index')
            ->with('success', 'Nilai berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        Nilai::where('jenis_data', 'nilai')
            ->findOrFail($id)
            ->delete();

        return redirect()->route('nilai.index')
            ->with('success', 'Data nilai berhasil dihapus.');
    }
}
