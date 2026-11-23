<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use Illuminate\Http\Request;

class TugasController extends Controller
{
    /**
     * Menampilkan daftar tugas dan filter status.
     */
    public function index(Request $request)
    {
        $query = Nilai::where('jenis_data', 'tugas');

        // Filter berdasarkan status tugas
        if ($request->filled('status')) {
            $query->where('status_tugas', $request->status);
        }

        // Urutkan berdasarkan deadline terdekat
        $tugas = $query->orderBy('deadline', 'asc')
            ->paginate(10)
            ->withQueryString();

        // Mengambil daftar mata kuliah untuk form tambah tugas
        $mataKuliah = Nilai::where('jenis_data', 'mata_kuliah')
            ->orderBy('nama_mk', 'asc')
            ->get();

        return view('tugas.index', compact(
            'tugas',
            'mataKuliah'
        ));
    }

    /**
     * Menyimpan tugas baru.
     */
    public function store(Request $request)
    {
        // Validasi input
        $data = $request->validate([
            'kode_mk' => 'required|string|max:30',
            'judul_tugas' => 'required|string|max:255',
            'deskripsi_tugas' => 'nullable|string',
            'deadline' => 'required|date',
        ]);

        // Memastikan mata kuliah tersedia
        $mataKuliah = Nilai::where('jenis_data', 'mata_kuliah')
            ->where('kode_mk', $data['kode_mk'])
            ->firstOrFail();

        // Menambahkan informasi tugas
        $data['jenis_data'] = 'tugas';
        $data['nama_mk'] = $mataKuliah->nama_mk;
        $data['nim'] = '240001';
        $data['nama_mahasiswa'] = 'Mahasiswa Dummy';
        $data['sks'] = $mataKuliah->sks;
        $data['semester'] = $mataKuliah->semester;
        $data['status_tugas'] = 'Belum Dikerjakan';

        // Menyimpan ke tabel nilais
        Nilai::create($data);

        return redirect()
            ->route('tugas.index')
            ->with('success', 'Tugas berhasil ditambahkan.');
    }

    /**
     * Mengubah status pengerjaan tugas.
     */
    public function updateStatus(Request $request, $id)
    {
        // Validasi status
        $request->validate([
            'status_tugas' => [
                'required',
                'in:Belum Dikerjakan,Sedang Dikerjakan,Selesai'
            ],
        ]);

        // Mencari tugas berdasarkan ID
        $tugas = Nilai::where('jenis_data', 'tugas')
            ->findOrFail($id);

        // Memperbarui status tugas
        $tugas->update([
            'status_tugas' => $request->status_tugas,
        ]);

        return redirect()
            ->route('tugas.index')
            ->with('success', 'Status tugas berhasil diperbarui.');
    }

    /**
     * Menghapus tugas.
     */
    public function destroy($id)
    {
        // Memastikan data yang dihapus adalah tugas
        $tugas = Nilai::where('jenis_data', 'tugas')
            ->findOrFail($id);

        $tugas->delete();

        return redirect()
            ->route('tugas.index')
            ->with('success', 'Tugas berhasil dihapus.');
    }
}
