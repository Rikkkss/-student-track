
@extends('layouts.app')

@section('title', 'Daftar Tugas')
@section('page-name', 'Daftar Tugas')

@section('content')

@php
    $dataHalaman = $tugas->getCollection();

    $jumlahBelum = $dataHalaman->where('status_tugas', 'Belum Dikerjakan')->count();
    $jumlahProses = $dataHalaman->where('status_tugas', 'Sedang Dikerjakan')->count();
    $jumlahSelesai = $dataHalaman->where('status_tugas', 'Selesai')->count();

    $jumlahTerlambat = $dataHalaman->filter(function ($item) {
        return $item->deadline
            && $item->deadline->isBefore(today())
            && $item->status_tugas !== 'Selesai';
    })->count();
@endphp

<!-- HEADER -->
<div class="page-heading">
    <div>
        <h1>Manajemen Daftar Tugas</h1>
        <p>Atur aktivitas akademik dan pantau deadline tugasmu.</p>
    </div>

    <div class="heading-icon">
        <i class="bi bi-list-check"></i>
    </div>
</div>

<!-- NOTIFIKASI -->
@if(session('success'))
    <div class="alert alert-success custom-alert mb-4">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger custom-alert mb-4">
        <strong>
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            Periksa kembali data tugas:
        </strong>

        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- STATISTIK -->
<div class="row g-3 mb-4">

    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon blue">
                    <i class="bi bi-clipboard-data-fill"></i>
                </div>
                <span class="stat-label">AKADEMIK</span>
            </div>

            <div class="stat-label">Total Tugas</div>
            <div class="stat-value">{{ $tugas->total() }}</div>
            <div class="stat-description">
                Total hasil pencarian/filter
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon orange">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <span class="stat-label">PENDING</span>
            </div>

            <div class="stat-label">Belum Dikerjakan</div>
            <div class="stat-value">{{ $jumlahBelum }}</div>
            <div class="stat-description">
                Pada halaman saat ini
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon purple">
                    <i class="bi bi-arrow-repeat"></i>
                </div>
                <span class="stat-label">PROGRES</span>
            </div>

            <div class="stat-label">Sedang Dikerjakan</div>
            <div class="stat-value">{{ $jumlahProses }}</div>
            <div class="stat-description">
                Pada halaman saat ini
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon green">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <span class="stat-label">SELESAI</span>
            </div>

            <div class="stat-label">Tugas Selesai</div>
            <div class="stat-value">{{ $jumlahSelesai }}</div>
            <div class="stat-description">
                Pada halaman saat ini
            </div>
        </div>
    </div>

</div>

<!-- RINGKASAN DEADLINE -->
@if($jumlahTerlambat > 0)
    <div class="alert alert-warning d-flex align-items-center gap-3 mb-4">
        <i class="bi bi-exclamation-triangle-fill fs-4"></i>

        <div>
            <strong>{{ $jumlahTerlambat }} tugas terlambat.</strong>
            <div class="small">
                Segera periksa dan perbarui status pengerjaan tugas tersebut.
            </div>
        </div>
    </div>
@endif

<!-- FORM TAMBAH TUGAS -->
<div class="card p-4 mb-4">

    <div class="d-flex align-items-center gap-3 mb-4">

        <div class="stat-icon blue">
            <i class="bi bi-plus-lg"></i>
        </div>

        <div>
            <h5 class="mb-1">Tambah Tugas Baru</h5>
            <div class="card-subtitle">
                Catat tugas baru beserta mata kuliah dan deadline.
            </div>
        </div>

    </div>

    <form action="{{ route('tugas.store') }}" method="POST">
        @csrf

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label">Mata Kuliah</label>

                <select name="kode_mk" class="form-select" required>
                    <option value="">Pilih Mata Kuliah</option>

                    @foreach($mataKuliah as $mk)
                        <option value="{{ $mk->kode_mk }}"
                            {{ old('kode_mk') == $mk->kode_mk ? 'selected' : '' }}>
                            {{ $mk->kode_mk }} - {{ $mk->nama_mk }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Judul Tugas</label>

                <input
                    type="text"
                    name="judul_tugas"
                    class="form-control"
                    placeholder="Contoh: Membuat laporan praktikum"
                    value="{{ old('judul_tugas') }}"
                    required>
            </div>

            <div class="col-md-12">
                <label class="form-label">Deskripsi Tugas</label>

                <textarea
                    name="deskripsi_tugas"
                    class="form-control"
                    rows="3"
                    placeholder="Tuliskan detail atau instruksi tugas...">{{ old('deskripsi_tugas') }}</textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label">Deadline Pengumpulan</label>

                <input
                    type="date"
                    name="deadline"
                    class="form-control"
                    value="{{ old('deadline') }}"
                    required>
            </div>

        </div>

        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>
                Tambah Tugas
            </button>
        </div>

    </form>
</div>

<!-- DAFTAR TUGAS -->
<div class="card p-4">

    <div class="d-flex align-items-center gap-3 mb-4">

        <div class="stat-icon blue">
            <i class="bi bi-clipboard-check-fill"></i>
        </div>

        <div>
            <h5 class="mb-1">Daftar Semua Tugas</h5>
            <div class="card-subtitle">
                Pantau status dan batas waktu pengumpulan tugas.
            </div>
        </div>

    </div>

    <!-- SEARCH DAN FILTER -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">

        <div style="width:280px;max-width:100%;">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-search text-secondary"></i>
                </span>

                <input
                    type="text"
                    id="searchTugas"
                    class="form-control border-start-0"
                    placeholder="Cari judul atau mata kuliah...">
            </div>
        </div>

        <form method="GET" action="{{ route('tugas.index') }}">
            <select
                name="status"
                class="form-select"
                style="min-width:200px;"
                onchange="this.form.submit()">

                <option value="">Semua Status</option>

                <option value="Belum Dikerjakan"
                    {{ request('status') == 'Belum Dikerjakan' ? 'selected' : '' }}>
                    Belum Dikerjakan
                </option>

                <option value="Sedang Dikerjakan"
                    {{ request('status') == 'Sedang Dikerjakan' ? 'selected' : '' }}>
                    Sedang Dikerjakan
                </option>

                <option value="Selesai"
                    {{ request('status') == 'Selesai' ? 'selected' : '' }}>
                    Selesai
                </option>

            </select>
        </form>

    </div>

    <!-- TABEL -->
    <div class="table-responsive">

        <table class="table align-middle" id="tableTugas">

            <thead>
                <tr>
                    <th>No.</th>
                    <th>Informasi Tugas</th>
                    <th>Mata Kuliah</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th>Ubah Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($tugas as $index => $item)

                    <tr>

                        <td class="text-secondary">
                            {{ $tugas->firstItem() + $index }}
                        </td>

                        <td style="min-width:190px;">
                            <div class="fw-bold">
                                {{ $item->judul_tugas }}
                            </div>

                            <small class="text-secondary">
                                {{ $item->deskripsi_tugas ?: 'Tidak ada deskripsi' }}
                            </small>
                        </td>

                        <td style="min-width:150px;">
                            <div class="fw-semibold">
                                {{ $item->nama_mk }}
                            </div>
                            <small class="text-secondary">
                                {{ $item->kode_mk }}
                            </small>
                        </td>

                        <td style="min-width:130px;">
                            <div class="fw-semibold">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ $item->deadline?->format('d-m-Y') ?? '-' }}
                            </div>

                            @if($item->deadline && $item->deadline->isBefore(today()) && $item->status_tugas !== 'Selesai')
                                <span class="badge bg-danger mt-1">
                                    <i class="bi bi-exclamation-circle me-1"></i>
                                    Terlambat
                                </span>
                            @elseif($item->deadline && $item->deadline->isToday() && $item->status_tugas !== 'Selesai')
                                <span class="badge bg-warning text-dark mt-1">
                                    Deadline Hari Ini
                                </span>
                            @endif
                        </td>

                        <td style="min-width:145px;">
                            @if($item->status_tugas == 'Selesai')
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Selesai
                                </span>
                            @elseif($item->status_tugas == 'Sedang Dikerjakan')
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-arrow-repeat me-1"></i>
                                    Sedang Dikerjakan
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="bi bi-clock me-1"></i>
                                    Belum Dikerjakan
                                </span>
                            @endif
                        </td>

                        <td style="min-width:175px;">
                            <form
                                action="{{ route('tugas.status', $item->id) }}"
                                method="POST">

                                @csrf
                                @method('PATCH')

                                <select
                                    name="status_tugas"
                                    class="form-select form-select-sm"
                                    onchange="this.form.submit()">

                                    <option value="Belum Dikerjakan"
                                        {{ $item->status_tugas == 'Belum Dikerjakan' ? 'selected' : '' }}>
                                        Belum Dikerjakan
                                    </option>

                                    <option value="Sedang Dikerjakan"
                                        {{ $item->status_tugas == 'Sedang Dikerjakan' ? 'selected' : '' }}>
                                        Sedang Dikerjakan
                                    </option>

                                    <option value="Selesai"
                                        {{ $item->status_tugas == 'Selesai' ? 'selected' : '' }}>
                                        Selesai
                                    </option>

                                </select>
                            </form>
                        </td>

                        <td class="text-center">

                            <form
                                action="{{ route('tugas.destroy', $item->id) }}"
                                method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus tugas ini?')">

                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash3 me-1"></i>
                                    Hapus
                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-clipboard-x fs-1 text-secondary"></i>

                            <h6 class="mt-3">Belum Ada Tugas</h6>

                            <p class="text-secondary mb-0">
                                Tambahkan tugas baru melalui formulir di atas.
                            </p>
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

        <div id="searchEmpty" class="text-center py-4 d-none">
            <i class="bi bi-search fs-2 text-secondary"></i>
            <p class="text-secondary mt-2 mb-0">
                Tugas tidak ditemukan.
            </p>
        </div>

    </div>

    <!-- PAGINATION -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-3 pt-3 border-top">

        <span class="text-secondary" style="font-size:11px;">
            Menampilkan {{ $tugas->firstItem() ?? 0 }}
            sampai {{ $tugas->lastItem() ?? 0 }}
            dari {{ $tugas->total() }} tugas
        </span>

        {{ $tugas->appends(request()->query())->links('pagination::bootstrap-5') }}

    </div>

</div>

@endsection

@push('scripts')
<script>
    // Pencarian tugas pada halaman aktif
    const searchTugas = document.getElementById('searchTugas');
    const tableTugas = document.getElementById('tableTugas');
    const searchEmpty = document.getElementById('searchEmpty');

    searchTugas.addEventListener('input', function () {
        const keyword = this.value.toLowerCase();
        const rows = tableTugas.querySelectorAll('tbody tr');

        let visibleCount = 0;

        rows.forEach(function (row) {
            const cocok = row.textContent.toLowerCase().includes(keyword);

            row.style.display = cocok ? '' : 'none';

            if (cocok) {
                visibleCount++;
            }
        });

        searchEmpty.classList.toggle(
            'd-none',
            keyword === '' || visibleCount > 0
        );
    });
</script>
@endpush
