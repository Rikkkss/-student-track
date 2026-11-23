<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    public function index()
    {
        $mataKuliah = Nilai::where('jenis_data', 'mata_kuliah')
            ->latest()
            ->get();

        return view('mata-kuliah.index', compact('mataKuliah'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode_mk' => 'required|string|max:30',
            'nama_mk' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|string|max:20',
            'dosen_pengampu' => 'nullable|string|max:255',
        ]);

        $data['jenis_data'] = 'mata_kuliah';
        $data['nim'] = '240001';
        $data['nama_mahasiswa'] = 'Mahasiswa Dummy';

        Nilai::create($data);

        return redirect()->route('mata-kuliah.index')
            ->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        Nilai::where('jenis_data', 'mata_kuliah')
            ->findOrFail($id)
            ->delete();

        return redirect()->route('mata-kuliah.index')
            ->with('success', 'Mata kuliah berhasil dihapus.');
    }
}
