<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Carbon\Carbon;

class ExcelController extends Controller
{
    public function index()
    {
        return view('excel.index');
    }

    // EXPORT DATA NILAI KE EXCEL
    public function export()
    {
        $dataNilai = Nilai::where('jenis_data', 'nilai')
            ->orderBy('tanggal_penilaian')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Data Nilai');

        $headers = [
            'NIM',
            'Nama Mahasiswa',
            'Kode MK',
            'Mata Kuliah',
            'SKS',
            'Semester',
            'Nilai Tugas',
            'Nilai UTS',
            'Nilai UAS',
            'Nilai Praktikum',
            'Tanggal Penilaian'
        ];

        $sheet->fromArray($headers, null, 'A1');

        $baris = 2;

        foreach ($dataNilai as $item) {
            $sheet->fromArray([
                $item->nim,
                $item->nama_mahasiswa,
                $item->kode_mk,
                $item->nama_mk,
                $item->sks,
                $item->semester,
                $item->nilai_tugas,
                $item->nilai_uts,
                $item->nilai_uas,
                $item->nilai_praktikum,
                $item->tanggal_penilaian
                    ? Carbon::parse($item->tanggal_penilaian)->format('Y-m-d')
                    : ''
            ], null, 'A' . $baris);

            $baris++;
        }

        // Tampilan header
        $sheet->getStyle('A1:K1')->getFont()->setBold(true);
        $sheet->getStyle('A1:K1')->getFont()->getColor()
            ->setARGB('FFFFFFFF');

        $sheet->getStyle('A1:K1')->getFill()
            ->setFillType('solid')
            ->getStartColor()
            ->setARGB('FF1D4ED8');

        // Lebar kolom otomatis
        foreach (range('A', 'K') as $kolom) {
            $sheet->getColumnDimension($kolom)->setAutoSize(true);
        }

        $sheet->freezePane('A2');

        if ($baris > 2) {
            $sheet->setAutoFilter('A1:K' . ($baris - 1));
        }

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(
            function () use ($writer) {
                $writer->save('php://output');
            },
            'student-track-data-nilai.xlsx',
            [
                'Content-Type' =>
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ]
        );
    }

    // IMPORT DATA NILAI DARI EXCEL
    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls,csv|max:5120'
        ], [
            'file_excel.required' => 'Silakan pilih file Excel terlebih dahulu.',
            'file_excel.mimes' => 'Format file harus XLSX, XLS, atau CSV.',
            'file_excel.max' => 'Ukuran file maksimal 5 MB.'
        ]);

        try {
            $file = $request->file('file_excel');

            $spreadsheet = IOFactory::load($file->getPathname());

            $sheet = $spreadsheet->getActiveSheet();

            $rows = $sheet->toArray(null, true, true, true);

            $jumlahImport = 0;

            DB::transaction(function () use ($rows, &$jumlahImport) {

                foreach ($rows as $nomorBaris => $row) {

                    // Lewati baris header
                    if ($nomorBaris == 1) {
                        continue;
                    }

                    // Lewati baris kosong
                    $isiBaris = array_filter(
                        $row,
                        fn($value) => $value !== null && trim((string) $value) !== ''
                    );

                    if (empty($isiBaris)) {
                        continue;
                    }

                    $nim = trim((string) ($row['A'] ?? ''));
                    $nama = trim((string) ($row['B'] ?? ''));
                    $kodeMK = trim((string) ($row['C'] ?? ''));
                    $namaMK = trim((string) ($row['D'] ?? ''));

                    $sks = $row['E'] ?? null;
                    $semester = $row['F'] ?? null;
                    $tugas = $row['G'] ?? null;
                    $uts = $row['H'] ?? null;
                    $uas = $row['I'] ?? null;
                    $praktikum = $row['J'] ?? null;
                    $tanggal = $row['K'] ?? null;

                    // Validasi data wajib
                    if (
                        $nim === '' ||
                        $nama === '' ||
                        $kodeMK === '' ||
                        $namaMK === ''
                    ) {
                        throw ValidationException::withMessages([
                            'file_excel' =>
                                "Data wajib belum lengkap pada baris {$nomorBaris}."
                        ]);
                    }

                    // Validasi angka
                    $angka = [
                        'SKS' => $sks,
                        'Semester' => $semester,
                        'Nilai Tugas' => $tugas,
                        'Nilai UTS' => $uts,
                        'Nilai UAS' => $uas,
                        'Nilai Praktikum' => $praktikum
                    ];

                    foreach ($angka as $label => $nilai) {
                        if (!is_numeric($nilai)) {
                            throw ValidationException::withMessages([
                                'file_excel' =>
                                    "{$label} harus berupa angka pada baris {$nomorBaris}."
                            ]);
                        }
                    }

                    if ($sks < 1 || $sks > 6) {
                        throw ValidationException::withMessages([
                            'file_excel' =>
                                "SKS harus 1 sampai 6 pada baris {$nomorBaris}."
                        ]);
                    }

                    if ($semester < 1 || $semester > 8) {
                        throw ValidationException::withMessages([
                            'file_excel' =>
                                "Semester harus 1 sampai 8 pada baris {$nomorBaris}."
                        ]);
                    }

                    foreach ([$tugas, $uts, $uas, $praktikum] as $nilai) {
                        if ($nilai < 0 || $nilai > 100) {
                            throw ValidationException::withMessages([
                                'file_excel' =>
                                    "Nilai harus berada pada rentang 0 sampai 100 pada baris {$nomorBaris}."
                            ]);
                        }
                    }

                    // Konversi tanggal Excel
                    try {
                        if ($tanggal === null || $tanggal === '') {
                            throw new \Exception('Tanggal kosong');
                        }

                        if (is_numeric($tanggal)) {
                            $tanggalPenilaian = ExcelDate::excelToDateTimeObject(
                                (float) $tanggal
                            )->format('Y-m-d');
                        } else {
                            $tanggalPenilaian = Carbon::parse($tanggal)
                                ->format('Y-m-d');
                        }
                    } catch (\Throwable $e) {
                        throw ValidationException::withMessages([
                            'file_excel' =>
                                "Tanggal tidak valid pada baris {$nomorBaris}. Gunakan format YYYY-MM-DD."
                        ]);
                    }

                    // Hitung nilai akhir
                    $nilaiAkhir =
                        (0.20 * $tugas) +
                        (0.30 * $uts) +
                        (0.30 * $uas) +
                        (0.20 * $praktikum);

                    // Tentukan nilai huruf
                    if ($nilaiAkhir >= 85) {
                        $nilaiHuruf = 'A';
                    } elseif ($nilaiAkhir >= 75) {
                        $nilaiHuruf = 'B';
                    } elseif ($nilaiAkhir >= 65) {
                        $nilaiHuruf = 'C';
                    } elseif ($nilaiAkhir >= 50) {
                        $nilaiHuruf = 'D';
                    } else {
                        $nilaiHuruf = 'E';
                    }

                    // Simpan ke tabel nilais
                    Nilai::create([
                        'jenis_data' => 'nilai',
                        'nim' => $nim,
                        'nama_mahasiswa' => $nama,
                        'kode_mk' => $kodeMK,
                        'nama_mk' => $namaMK,
                        'sks' => $sks,
                        'semester' => $semester,
                        'nilai_tugas' => $tugas,
                        'nilai_uts' => $uts,
                        'nilai_uas' => $uas,
                        'nilai_praktikum' => $praktikum,
                        'nilai_akhir' => round($nilaiAkhir, 2),
                        'nilai_huruf' => $nilaiHuruf,
                        'tanggal_penilaian' => $tanggalPenilaian
                    ]);

                    $jumlahImport++;
                }
            });

            if ($jumlahImport === 0) {
                return back()->withErrors([
                    'file_excel' => 'Tidak ada data yang dapat diimport.'
                ]);
            }

            return redirect()
                ->route('excel.index')
                ->with(
                    'success',
                    "{$jumlahImport} data nilai berhasil diimport."
                );

        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            return back()->withErrors([
                'file_excel' =>
                    'Gagal membaca file Excel. Periksa format dan isi file.'
            ]);
        }
    }
}
