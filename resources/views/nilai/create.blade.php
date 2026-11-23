
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Nilai - Student Track</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/student-track.css') }}">
    
    <style>
        body { background: #f4f6fb; font-family: Arial, sans-serif; }
        .sidebar { min-height: 100vh; background: #172554; padding: 25px 15px; }
        .sidebar h4 { color: white; }
        .sidebar a {
            display: block; color: #cbd5e1; text-decoration: none;
            padding: 12px; border-radius: 8px; margin-bottom: 5px;
        }
        .sidebar a:hover, .sidebar a.active { background: #2563eb; color: white; }
        .content { padding: 30px; }
        .custom-card {
            border: none; border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,.05);
        }
    </style>
</head>
<body>

<div class="container-fluid">
<div class="row">

    <div class="col-md-2 sidebar">
        <h4 class="mb-4">Student Track</h4>
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <a href="{{ route('mata-kuliah.index') }}">Mata Kuliah</a>
        <a href="{{ route('nilai.index') }}" class="active">Nilai</a>
        <a href="{{ route('tugas.index') }}">Daftar Tugas</a>
        <a href="{{ route('grafik.index') }}">Grafik Nilai</a>
        <a href="{{ route('excel.index') }}">Import / Export Excel</a>
    </div>

    <div class="col-md-10 content">

        <h2 class="fw-bold">Tambah Nilai</h2>
        <p class="text-secondary">Masukkan komponen penilaian mata kuliah.</p>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card custom-card p-4">
            <form action="{{ route('nilai.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Mata Kuliah</label>
                    <select name="kode_mk" id="mataKuliah"
                            class="form-select" required>
                        <option value="">Pilih Mata Kuliah</option>
                        @foreach($mataKuliah as $mk)
                            <option value="{{ $mk->kode_mk }}"
                                    data-nama="{{ $mk->nama_mk }}"
                                    {{ old('kode_mk') == $mk->kode_mk ? 'selected' : '' }}>
                                {{ $mk->kode_mk }} - {{ $mk->nama_mk }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="nama_mk" id="namaMK">
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nilai Tugas (20%)</label>
                        <input type="number" name="nilai_tugas"
                               class="form-control nilai-input"
                               min="0" max="100" step="0.01"
                               value="{{ old('nilai_tugas') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nilai UTS (30%)</label>
                        <input type="number" name="nilai_uts"
                               class="form-control nilai-input"
                               min="0" max="100" step="0.01"
                               value="{{ old('nilai_uts') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nilai UAS (30%)</label>
                        <input type="number" name="nilai_uas"
                               class="form-control nilai-input"
                               min="0" max="100" step="0.01"
                               value="{{ old('nilai_uas') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nilai Praktikum (20%)</label>
                        <input type="number" name="nilai_praktikum"
                               class="form-control nilai-input"
                               min="0" max="100" step="0.01"
                               value="{{ old('nilai_praktikum') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tanggal Penilaian</label>
                        <input type="date" name="tanggal_penilaian"
                               class="form-control"
                               value="{{ old('tanggal_penilaian', date('Y-m-d')) }}"
                               required>
                    </div>
                </div>

                <div class="card bg-light border-0 p-3 mt-4">
                    <h6>Pratinjau Perhitungan Nilai</h6>
                    <h3 id="nilaiAkhir">0.00</h3>
                    <p class="mb-0">
                        Predikat: <strong id="nilaiHuruf">-</strong>
                    </p>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        Simpan Nilai
                    </button>
                    <a href="{{ route('nilai.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>
                </div>
            </form>
        </div>

    </div>
</div>
</div>

<script>
    const mataKuliah = document.getElementById('mataKuliah');
    const namaMK = document.getElementById('namaMK');

    function updateMataKuliah() {
        const option = mataKuliah.options[mataKuliah.selectedIndex];
        namaMK.value = option.dataset.nama || '';
    }

    mataKuliah.addEventListener('change', updateMataKuliah);
    updateMataKuliah();

    function hitungNilai() {
        const tugas = parseFloat(document.querySelector('[name="nilai_tugas"]').value) || 0;
        const uts = parseFloat(document.querySelector('[name="nilai_uts"]').value) || 0;
        const uas = parseFloat(document.querySelector('[name="nilai_uas"]').value) || 0;
        const praktikum = parseFloat(document.querySelector('[name="nilai_praktikum"]').value) || 0;

        const akhir = tugas * 0.20 + uts * 0.30 + uas * 0.30 + praktikum * 0.20;

        let huruf = '-';
        if (akhir >= 85) huruf = 'A';
        else if (akhir >= 75) huruf = 'B';
        else if (akhir >= 65) huruf = 'C';
        else if (akhir >= 50) huruf = 'D';
        else huruf = 'E';

        document.getElementById('nilaiAkhir').textContent = akhir.toFixed(2);
        document.getElementById('nilaiHuruf').textContent = huruf;
    }

    document.querySelectorAll('.nilai-input').forEach(input => {
        input.addEventListener('input', hitungNilai);
    });

    hitungNilai();
</script>

</body>
</html>
