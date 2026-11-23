
@extends('layouts.app')

@section('title', 'Grafik Nilai')
@section('page-name', 'Grafik Nilai')

@section('content')

<!-- HEADER -->
<div class="page-heading">
    <div>
        <h1>Analisis Grafik Nilai</h1>
        <p>Visualisasi dan evaluasi perkembangan akademikmu.</p>
    </div>

    <div class="heading-icon">
        <i class="bi bi-bar-chart-line-fill"></i>
    </div>
</div>

<!-- STATISTIK -->
<div class="row g-3 mb-4">

    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon blue">
                    <i class="bi bi-journal-check"></i>
                </div>
                <span class="stat-label">AKADEMIK</span>
            </div>
            <div class="stat-label">Total Data Nilai</div>
            <div class="stat-value">{{ $totalNilai }}</div>
            <div class="stat-description">Seluruh penilaian tersimpan</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon green">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <span class="stat-label">PERFORMA</span>
            </div>
            <div class="stat-label">Rata-rata Nilai</div>
            <div class="stat-value">
                {{ number_format($rataRata, 2) }}
            </div>
            <div class="stat-description">Rata-rata seluruh nilai akhir</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon purple">
                    <i class="bi bi-trophy-fill"></i>
                </div>
                <span class="stat-label">TERTINGGI</span>
            </div>
            <div class="stat-label">Nilai Tertinggi</div>
            <div class="stat-value">
                {{ number_format($nilaiTertinggi, 2) }}
            </div>
            <div class="stat-description">Pencapaian nilai terbaik</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon orange">
                    <i class="bi bi-clipboard-data"></i>
                </div>
                <span class="stat-label">TERENDAH</span>
            </div>
            <div class="stat-label">Nilai Terendah</div>
            <div class="stat-value">
                {{ number_format($nilaiTerendah, 2) }}
            </div>
            <div class="stat-description">Nilai akhir terendah</div>
        </div>
    </div>

</div>

<!-- GRAFIK RATA-RATA MATA KULIAH -->
<div class="card p-4 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div>
            <h5 class="mb-1">Rata-rata Nilai per Mata Kuliah</h5>
            <div class="card-subtitle">
                Perbandingan rata-rata nilai akhir setiap mata kuliah.
            </div>
        </div>

        <span class="badge bg-primary">Grafik Batang</span>
    </div>

    <div class="chart-container" style="height:350px;">
        <canvas id="grafikMataKuliah"></canvas>
    </div>

    @if($totalNilai == 0)
        <p class="text-center text-secondary mt-3">
            Belum ada data nilai untuk ditampilkan.
        </p>
    @endif

</div>

<!-- GRAFIK PREDIKAT DAN PERKEMBANGAN -->
<div class="row g-4 mb-4">

    <div class="col-xl-5 col-lg-5">
        <div class="card p-4 h-100">

            <div class="mb-3">
                <h5 class="mb-1">Distribusi Predikat</h5>
                <div class="card-subtitle">
                    Jumlah mahasiswa berdasarkan predikat nilai.
                </div>
            </div>

            <div class="chart-container" style="height:300px;">
                <canvas id="grafikPredikat"></canvas>
            </div>

        </div>
    </div>

    <div class="col-xl-7 col-lg-7">
        <div class="card p-4 h-100">

            <div class="mb-3">
                <h5 class="mb-1">Perkembangan Nilai</h5>
                <div class="card-subtitle">
                    Rata-rata nilai akhir berdasarkan bulan penilaian.
                </div>
            </div>

            <div class="chart-container" style="height:300px;">
                <canvas id="grafikPerkembangan"></canvas>
            </div>

        </div>
    </div>

</div>

<!-- KETERANGAN -->
<div class="card p-4">
    <div class="d-flex align-items-center gap-3">
        <div class="stat-icon blue">
            <i class="bi bi-info-circle-fill"></i>
        </div>

        <div>
            <h6 class="mb-1">Informasi Grafik</h6>
            <p class="text-secondary mb-0" style="font-size:12px;">
                Seluruh grafik diperoleh dari data nilai pada database.
                Nilai akhir digunakan sebagai dasar perhitungan rata-rata
                dan distribusi predikat.
            </p>
        </div>
    </div>
</div>

@endsection

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Data dari Laravel
    const labelMK = @json($labelMataKuliah);
    const nilaiMK = @json($nilaiMataKuliah);

    const labelPredikat = @json($labelPredikat);
    const jumlahPredikat = @json($jumlahPredikat);

    const labelPeriode = @json($labelPeriode);
    const nilaiPeriode = @json($nilaiPeriode);

    // Konfigurasi umum
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#8490a5';

    // 1. Grafik rata-rata mata kuliah
    new Chart(document.getElementById('grafikMataKuliah'), {
        type: 'bar',

        data: {
            labels: labelMK,

            datasets: [{
                label: 'Rata-rata Nilai',
                data: nilaiMK,
                backgroundColor: '#3568ed',
                borderRadius: 7,
                maxBarThickness: 55
            }]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: {
                    display: false
                },

                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Nilai: ' + context.raw;
                        }
                    }
                }
            },

            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        stepSize: 20
                    },
                    grid: {
                        color: '#edf0f5'
                    }
                },

                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // 2. Grafik distribusi predikat
    new Chart(document.getElementById('grafikPredikat'), {
        type: 'doughnut',

        data: {
            labels: labelPredikat,

            datasets: [{
                data: jumlahPredikat,

                backgroundColor: [
                    '#19a875',
                    '#3568ed',
                    '#f3a43b',
                    '#8763dc',
                    '#e45b68'
                ],

                borderWidth: 3,
                borderColor: '#ffffff',
                hoverOffset: 8
            }]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 18
                    }
                }
            },

            cutout: '65%'
        }
    });

    // 3. Grafik perkembangan nilai
    new Chart(document.getElementById('grafikPerkembangan'), {
        type: 'line',

        data: {
            labels: labelPeriode,

            datasets: [{
                label: 'Rata-rata Nilai',
                data: nilaiPeriode,

                borderColor: '#3568ed',
                backgroundColor: 'rgba(53,104,237,0.12)',

                fill: true,
                tension: 0.35,

                pointRadius: 5,
                pointHoverRadius: 7,
                pointBackgroundColor: '#3568ed'
            }]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: {
                    display: false
                }
            },

            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        stepSize: 20
                    },
                    grid: {
                        color: '#edf0f5'
                    }
                },

                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

</script>

@endpush
