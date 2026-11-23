
@extends('layouts.app')

@section('title', 'Mata Kuliah')
@section('page-name', 'Mata Kuliah')

@section('content')

<!-- PAGE HEADER -->
<div class="page-heading">
    <div>
        <h1>Manajemen Mata Kuliah</h1>
        <p>Kelola dan pantau seluruh mata kuliah yang sedang kamu pelajari.</p>
    </div>

    <div class="heading-icon">
        <i class="bi bi-book-fill"></i>
    </div>
</div>

<!-- NOTIFIKASI -->
@if($errors->any())
    <div class="alert alert-danger custom-alert mb-4">
        <i class="bi bi-exclamation-circle-fill me-2"></i>
        <strong>Periksa kembali data yang dimasukkan:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- STATISTIK -->
<div class="row g-3 mb-4">

    <div class="col-md-6">
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon blue">
                    <i class="bi bi-book-half"></i>
                </div>
                <span class="stat-label">AKADEMIK</span>
            </div>

            <div class="stat-label">Total Mata Kuliah</div>
            <div class="stat-value">{{ $mataKuliah->count() }}</div>
            <div class="stat-description">
                Mata kuliah yang terdaftar
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon green">
                    <i class="bi bi-journal-bookmark-fill"></i>
                </div>
                <span class="stat-label">BEBAN STUDI</span>
            </div>

            <div class="stat-label">Total SKS</div>
            <div class="stat-value">
                {{ $mataKuliah->sum('sks') }}
            </div>
            <div class="stat-description">
                Akumulasi SKS seluruh mata kuliah
            </div>
        </div>
    </div>

</div>

<!-- FORM TAMBAH MATA KULIAH -->
<div class="card p-4 mb-4">

    <div class="d-flex align-items-center gap-3 mb-4">
        <div class="stat-icon blue">
            <i class="bi bi-plus-lg"></i>
        </div>

        <div>
            <h5 class="mb-1">Tambah Mata Kuliah</h5>
            <div class="card-subtitle">
                Masukkan informasi mata kuliah baru.
            </div>
        </div>
    </div>

    <form action="{{ route('mata-kuliah.store') }}" method="POST">
        @csrf

        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label">Kode Mata Kuliah</label>
                <input
                    type="text"
                    name="kode_mk"
                    class="form-control"
                    placeholder="Contoh: IF111"
                    value="{{ old('kode_mk') }}"
                    required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Nama Mata Kuliah</label>
                <input
                    type="text"
                    name="nama_mk"
                    class="form-control"
                    placeholder="Masukkan nama mata kuliah"
                    value="{{ old('nama_mk') }}"
                    required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Jumlah SKS</label>
                <input
                    type="number"
                    name="sks"
                    class="form-control"
                    placeholder="Contoh: 3"
                    min="1"
                    max="6"
                    value="{{ old('sks') }}"
                    required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Semester</label>
                <select name="semester" class="form-select" required>
                    <option value="">Pilih Semester</option>

                    @for($i = 1; $i <= 8; $i++)
                        <option value="{{ $i }}"
                            {{ old('semester') == $i ? 'selected' : '' }}>
                            Semester {{ $i }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Dosen Pengampu</label>
                <input
                    type="text"
                    name="dosen_pengampu"
                    class="form-control"
                    placeholder="Masukkan nama dosen"
                    value="{{ old('dosen_pengampu') }}">
            </div>

        </div>

        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>
                Tambah Mata Kuliah
            </button>
        </div>

    </form>
</div>

<!-- DAFTAR MATA KULIAH -->
<div class="card p-4">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <h5 class="mb-1">Daftar Mata Kuliah</h5>
            <div class="card-subtitle">
                Seluruh mata kuliah yang telah ditambahkan.
            </div>
        </div>

        <div style="width:260px;max-width:100%;">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-search text-secondary"></i>
                </span>

                <input
                    type="text"
                    id="searchMataKuliah"
                    class="form-control border-start-0"
                    placeholder="Cari mata kuliah...">
            </div>
        </div>

    </div>

    <div class="table-responsive">

        <table class="table align-middle" id="tableMataKuliah">

            <thead>
                <tr>
                    <th width="55">No.</th>
                    <th>Kode</th>
                    <th>Nama Mata Kuliah</th>
                    <th>SKS</th>
                    <th>Semester</th>
                    <th>Dosen Pengampu</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($mataKuliah as $index => $mk)

                    <tr>

                        <td class="text-secondary">
                            {{ $index + 1 }}
                        </td>

                        <td>
                            <span class="badge"
                                  style="background:#edf3ff;color:#3568ed;">
                                {{ $mk->kode_mk }}
                            </span>
                        </td>

                        <td>
                            <div class="fw-bold">
                                {{ $mk->nama_mk }}
                            </div>
                        </td>

                        <td>
                            <span class="fw-semibold">
                                {{ $mk->sks }} SKS
                            </span>
                        </td>

                        <td>
                            <span class="badge bg-light text-dark border">
                                Semester {{ $mk->semester }}
                            </span>
                        </td>

                        <td>
                            <span class="text-secondary">
                                <i class="bi bi-person-fill me-1"></i>
                                {{ $mk->dosen_pengampu ?? 'Belum ditentukan' }}
                            </span>
                        </td>

                        <td class="text-center">

                            <form
                                action="{{ route('mata-kuliah.destroy', $mk->id) }}"
                                method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus mata kuliah {{ $mk->nama_mk }}?')">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash3 me-1"></i>
                                    Hapus
                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7" class="text-center py-5">

                            <i class="bi bi-book fs-1 text-secondary"></i>

                            <h6 class="mt-3">Belum Ada Mata Kuliah</h6>

                            <p class="text-secondary mb-0">
                                Silakan tambahkan mata kuliah melalui formulir di atas.
                            </p>

                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

        <div id="searchEmpty" class="text-center py-4 d-none">
            <i class="bi bi-search fs-2 text-secondary"></i>
            <p class="text-secondary mt-2 mb-0">
                Mata kuliah tidak ditemukan.
            </p>
        </div>

    </div>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3 pt-3 border-top">

        <span class="text-secondary" style="font-size:11px;">
            Menampilkan {{ $mataKuliah->count() }} mata kuliah
        </span>

        <span class="text-secondary" style="font-size:11px;">
            Student Track Academic Management
        </span>

    </div>

</div>

@endsection

@push('scripts')
<script>
    // Pencarian mata kuliah secara langsung
    const searchInput = document.getElementById('searchMataKuliah');
    const table = document.getElementById('tableMataKuliah');
    const emptyMessage = document.getElementById('searchEmpty');

    searchInput.addEventListener('input', function () {
        const keyword = this.value.toLowerCase();
        const rows = table.querySelectorAll('tbody tr');

        let visibleCount = 0;

        rows.forEach(function (row) {
            const text = row.textContent.toLowerCase();
            const match = text.includes(keyword);

            row.style.display = match ? '' : 'none';

            if (match) {
                visibleCount++;
            }
        });

        emptyMessage.classList.toggle(
            'd-none',
            visibleCount !== 0 || keyword === ''
        );
    });
</script>
@endpush
