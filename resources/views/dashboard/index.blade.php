
@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-name', 'Dashboard')

@section('content')

<div class="page-heading">
    <div>
        <h1>Dashboard Overview</h1>
        <p>Selamat datang kembali! Pantau perkembangan akademikmu di sini.</p>
    </div>

    <div class="heading-icon">
        <i class="bi bi-grid-1x2-fill"></i>
    </div>
</div>

<!-- STATISTIK -->
<div class="row g-3 mb-4">

    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon blue">
                    <i class="bi bi-book-fill"></i>
                </div>
                <span class="stat-label">AKADEMIK</span>
            </div>
            <div class="stat-label">Total Mata Kuliah</div>
            <div class="stat-value">{{ $jumlahMK }}</div>
            <div class="stat-description">Mata kuliah terdaftar</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon green">
                    <i class="bi bi-journal-check"></i>
                </div>
                <span class="stat-label">PENILAIAN</span>
            </div>
            <div class="stat-label">Total Data Nilai</div>
            <div class="stat-value">{{ $jumlahNilai }}</div>
            <div class="stat-description">Data penilaian tersimpan</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon orange">
                    <i class="bi bi-clipboard-check-fill"></i>
                </div>
                <span class="stat-label">AKTIVITAS</span>
            </div>
            <div class="stat-label">Total Tugas</div>
            <div class="stat-value">{{ $jumlahTugas }}</div>
            <div class="stat-description">Tugas yang tercatat</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon purple">
                    <i class="bi bi-award-fill"></i>
                </div>
                <span class="stat-label">PERFORMA</span>
            </div>
            <div class="stat-label">Rata-rata Nilai</div>
            <div class="stat-value">{{ number_format($rataRata, 2) }}</div>
            <div class="stat-description">Akumulasi nilai akademik</div>
        </div>
    </div>

</div>

<!-- RINGKASAN DAN DEADLINE -->
<div class="row g-3 mb-4">

    <div class="col-lg-5">
        <div class="card p-4 h-100">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="mb-1">Ringkasan Tugas</h5>
                    <div class="card-subtitle">Status penyelesaian tugas</div>
                </div>

                <div class="stat-icon blue">
                    <i class="bi bi-list-check"></i>
                </div>
            </div>

            @php
                $persentaseSelesai = $jumlahTugas > 0
                    ? ($tugasSelesai / $jumlahTugas) * 100
                    : 0;
            @endphp

            <div class="d-flex justify-content-between mb-2">
                <span class="stat-label">Tugas selesai</span>
                <strong>{{ $tugasSelesai }} / {{ $jumlahTugas }}</strong>
            </div>

            <div class="progress mb-4" style="height: 9px; border-radius: 20px;">
                <div class="progress-bar bg-success"
                     style="width: {{ $persentaseSelesai }}%">
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <span class="badge bg-success">Selesai</span>
                </div>
                <strong>{{ $tugasSelesai }}</strong>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <span class="badge bg-secondary">Belum Dikerjakan</span>
                </div>
                <strong>{{ $tugasBelum }}</strong>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="badge bg-warning text-dark">Belum selesai lainnya</span>
                </div>
                <strong>{{ max(0, $jumlahTugas - $tugasSelesai - $tugasBelum) }}</strong>
            </div>

        </div>
    </div>

    <div class="col-lg-7">
        <div class="card p-4 h-100">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="mb-1">Deadline Terdekat</h5>
                    <div class="card-subtitle">Prioritaskan tugas yang segera dikumpulkan</div>
                </div>

                <a href="{{ route('tugas.index') }}"
                   class="btn btn-sm btn-primary">
                    Lihat Semua
                </a>
            </div>

            @forelse($tugasTerbaru as $tugas)
                <div class="d-flex align-items-start justify-content-between gap-3 py-3 border-bottom">

                    <div class="d-flex gap-3">
                        <div class="stat-icon orange"
                             style="width:36px;height:36px;min-width:36px;font-size:15px;">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>

                        <div>
                            <div class="fw-bold" style="font-size:12px;">
                                {{ $tugas->judul_tugas }}
                            </div>

                            <div class="card-subtitle mt-1">
                                {{ $tugas->nama_mk }}
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <div class="fw-semibold" style="font-size:11px;">
                            {{ $tugas->deadline?->format('d M Y') ?? '-' }}
                        </div>

                        <div class="card-subtitle mt-1">
                            @if($tugas->status_tugas == 'Selesai')
                                <span class="text-success">Selesai</span>
                            @elseif($tugas->deadline && $tugas->deadline->isPast())
                                <span class="text-danger">Terlambat</span>
                            @else
                                <span class="text-primary">Belum selesai</span>
                            @endif
                        </div>
                    </div>

                </div>
            @empty
                <div class="text-center py-5">
                    <i class="bi bi-calendar-check fs-1 text-secondary"></i>
                    <p class="text-secondary mt-2 mb-0">Belum ada tugas.</p>
                </div>
            @endforelse

        </div>
    </div>

</div>

<!-- QUICK ACCESS -->
<div class="card p-4">
    <h5 class="mb-1">Akses Cepat</h5>
    <p class="card-subtitle mb-3">Pilih aktivitas yang ingin kamu lakukan.</p>

    <div class="row g-3">

        <div class="col-md-4">
            <a href="{{ route('nilai.create') }}"
               class="d-flex align-items-center gap-3 p-3 rounded-3"
               style="background:#edf3ff;color:#3568ed;">
                <i class="bi bi-plus-circle-fill fs-4"></i>
                <div>
                    <strong class="d-block">Tambah Nilai</strong>
                    <small>Input penilaian baru</small>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="{{ route('tugas.index') }}"
               class="d-flex align-items-center gap-3 p-3 rounded-3"
               style="background:#e8f8f1;color:#19a875;">
                <i class="bi bi-clipboard-plus-fill fs-4"></i>
                <div>
                    <strong class="d-block">Kelola Tugas</strong>
                    <small>Atur tugas dan deadline</small>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="{{ route('grafik.index') }}"
               class="d-flex align-items-center gap-3 p-3 rounded-3"
               style="background:#f2edff;color:#8763dc;">
                <i class="bi bi-graph-up-arrow fs-4"></i>
                <div>
                    <strong class="d-block">Lihat Grafik</strong>
                    <small>Analisis performa nilai</small>
                </div>
            </a>
        </div>

    </div>
</div>

@endsection
