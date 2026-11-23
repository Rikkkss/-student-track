
@extends('layouts.app')

@section('title', 'Data Nilai')
@section('page-name', 'Data Nilai')

@section('content')

@php
    $rataRataHalaman = $nilai->count() > 0
        ? $nilai->getCollection()->avg('nilai_akhir')
        : 0;
@endphp

<!-- HEADER -->
<div class="page-heading">
    <div>
        <h1>Manajemen Data Nilai</h1>
        <p>Pantau perkembangan dan riwayat hasil akademikmu.</p>
    </div>

    <div class="heading-icon">
        <i class="bi bi-journal-check"></i>
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
            Terdapat kesalahan:
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

    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon blue">
                    <i class="bi bi-journal-bookmark-fill"></i>
                </div>
                <span class="stat-label">AKADEMIK</span>
            </div>

            <div class="stat-label">Total Data Nilai</div>
            <div class="stat-value">{{ $nilai->total() }}</div>
            <div class="stat-description">
                Seluruh penilaian tersimpan
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon green">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <span class="stat-label">PERFORMA</span>
            </div>

            <div class="stat-label">Rata-rata Halaman</div>
            <div class="stat-value">
                {{ number_format($rataRataHalaman ?? 0, 2) }}
            </div>
            <div class="stat-description">
                Rata-rata dari data pada halaman ini
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon purple">
                    <i class="bi bi-file-earmark-check-fill"></i>
                </div>
                <span class="stat-label">PENAMPILAN</span>
            </div>

            <div class="stat-label">Data Ditampilkan</div>
            <div class="stat-value">{{ $nilai->count() }}</div>
            <div class="stat-description">
                Data pada halaman saat ini
            </div>
        </div>
    </div>

</div>

<!-- DAFTAR PENILAIAN -->
<div class="card p-4 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

        <div class="d-flex align-items-center gap-3">
            <div class="stat-icon blue">
                <i class="bi bi-clipboard-data-fill"></i>
            </div>

            <div>
                <h5 class="mb-1">Riwayat Penilaian</h5>
                <div class="card-subtitle">
                    Kelola seluruh hasil penilaian mata kuliah.
                </div>
            </div>
        </div>

        <a href="{{ route('nilai.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>
            Tambah Nilai
        </a>

    </div>

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">

        <!-- PENCARIAN -->
        <div style="width:280px;max-width:100%;">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-search text-secondary"></i>
                </span>

                <input
                    type="text"
                    id="searchNilai"
                    class="form-control border-start-0"
                    placeholder="Cari mata kuliah...">
            </div>
        </div>

        <!-- PENGURUTAN -->
        <form method="GET" action="{{ route('nilai.index') }}">
            <select
                name="sort"
                class="form-select"
                style="min-width:190px;"
                onchange="this.form.submit()">

                <option value="">Urutkan Data</option>

                <option value="tanggal"
                    {{ request('sort') == 'tanggal' ? 'selected' : '' }}>
                    Tanggal Terbaru
                </option>

                <option value="tertinggi"
                    {{ request('sort') == 'tertinggi' ? 'selected' : '' }}>
                    Nilai Tertinggi
                </option>

                <option value="terendah"
                    {{ request('sort') == 'terendah' ? 'selected' : '' }}>
                    Nilai Terendah
                </option>

            </select>
        </form>

    </div>

    <!-- TABEL -->
    <div class="table-responsive">

        <table class="table align-middle" id="tableNilai">

            <thead>
                <tr>
                    <th>No.</th>
                    <th>Mata Kuliah</th>
                    <th>Tugas</th>
                    <th>UTS</th>
                    <th>UAS</th>
                    <th>Praktikum</th>
                    <th>Nilai Akhir</th>
                    <th>Predikat</th>
                    <th>Tanggal</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($nilai as $index => $item)

                    <tr>

                        <td class="text-secondary">
                            {{ $nilai->firstItem() + $index }}
                        </td>

                        <td>
                            <div class="fw-bold">
                                {{ $item->nama_mk }}
                            </div>

                            <small class="text-secondary">
                                {{ $item->kode_mk }}
                            </small>
                        </td>

                        <td>{{ number_format($item->nilai_tugas, 2) }}</td>
                        <td>{{ number_format($item->nilai_uts, 2) }}</td>
                        <td>{{ number_format($item->nilai_uas, 2) }}</td>
                        <td>{{ number_format($item->nilai_praktikum, 2) }}</td>

                        <td>
                            <strong style="color:#172554;">
                                {{ number_format($item->nilai_akhir, 2) }}
                            </strong>
                        </td>

                        <td>
                            @php
                                $warnaPredikat = match($item->nilai_huruf) {
                                    'A' => 'success',
                                    'B' => 'primary',
                                    'C' => 'warning',
                                    'D' => 'danger',
                                    'E' => 'dark',
                                    default => 'secondary'
                                };
                            @endphp

                            <span class="badge bg-{{ $warnaPredikat }}">
                                {{ $item->nilai_huruf }}
                            </span>
                        </td>

                        <td>
                            <span class="text-secondary">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ $item->tanggal_penilaian?->format('d-m-Y') ?? '-' }}
                            </span>
                        </td>

                        <td class="text-center">

                            <form
                                action="{{ route('nilai.destroy', $item->id) }}"
                                method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus data nilai {{ $item->nama_mk }}?')">

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
                        <td colspan="10" class="text-center py-5">

                            <i class="bi bi-journal-x fs-1 text-secondary"></i>

                            <h6 class="mt-3">Belum Ada Data Nilai</h6>

                            <p class="text-secondary mb-0">
                                Tambahkan penilaian mata kuliah untuk mulai mencatat hasil akademik.
                            </p>

                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

        <div id="searchEmpty" class="text-center py-4 d-none">
            <i class="bi bi-search fs-2 text-secondary"></i>
            <p class="text-secondary mt-2 mb-0">
                Data nilai tidak ditemukan.
            </p>
        </div>

    </div>

    <!-- PAGINATION -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-3 pt-3 border-top">

        <span class="text-secondary" style="font-size:11px;">
            Menampilkan {{ $nilai->firstItem() ?? 0 }}
            sampai {{ $nilai->lastItem() ?? 0 }}
            dari {{ $nilai->total() }} data
        </span>

        {{ $nilai->appends(request()->query())->links('pagination::bootstrap-5') }}

    </div>

</div>

@endsection

@push('scripts')
<script>
    // Pencarian data nilai pada halaman aktif
    const searchNilai = document.getElementById('searchNilai');
    const tableNilai = document.getElementById('tableNilai');
    const searchEmpty = document.getElementById('searchEmpty');

    searchNilai.addEventListener('input', function () {
        const keyword = this.value.toLowerCase();
        const rows = tableNilai.querySelectorAll('tbody tr');

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
