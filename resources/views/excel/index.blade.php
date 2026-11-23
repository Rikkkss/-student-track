
@extends('layouts.app')

@section('title', 'Import & Export')
@section('page-name', 'Import / Export')

@section('content')

<style>
    .excel-page {
        color: #172b4d;
    }

    .excel-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 28px;
    }

    .excel-heading h2 {
        font-size: 27px;
        font-weight: 800;
        margin-bottom: 7px;
    }

    .excel-heading p {
        color: #8492a6;
        margin: 0;
    }

    .excel-date {
        background: white;
        border: 1px solid #e8edf5;
        border-radius: 12px;
        padding: 11px 16px;
        color: #64748b;
        font-size: 13px;
    }

    .excel-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
        margin-bottom: 25px;
    }

    .excel-stat {
        background: white;
        border: 1px solid #e9eef7;
        border-radius: 16px;
        padding: 21px;
        box-shadow: 0 5px 18px rgba(30, 55, 90, .035);
    }

    .excel-stat small {
        color: #8794a8;
        font-weight: 600;
    }

    .excel-stat h3 {
        font-size: 27px;
        font-weight: 800;
        margin: 9px 0 3px;
        color: #172b4d;
    }

    .excel-stat .stat-icon {
        width: 42px;
        height: 42px;
        display: grid;
        place-items: center;
        border-radius: 12px;
        font-size: 20px;
        margin-bottom: 15px;
    }

    .stat-blue { background: #eaf1ff; color: #3568ee; }
    .stat-green { background: #e5f8f0; color: #16a36b; }
    .stat-orange { background: #fff2df; color: #e99a25; }

    .excel-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 22px;
        margin-bottom: 24px;
    }

    .excel-card {
        background: white;
        border: 1px solid #e8edf5;
        border-radius: 18px;
        padding: 27px;
        box-shadow: 0 7px 24px rgba(30, 55, 90, .045);
        min-width: 0;
    }

    .excel-card-top {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 23px;
    }

    .excel-icon {
        width: 49px;
        height: 49px;
        display: grid;
        place-items: center;
        border-radius: 14px;
        font-size: 23px;
        flex-shrink: 0;
    }

    .excel-card h4 {
        font-size: 19px;
        font-weight: 800;
        margin: 0 0 5px;
    }

    .excel-card-top p {
        color: #8b98aa;
        font-size: 12px;
        margin: 0;
    }

    .excel-description {
        color: #77869b;
        font-size: 13px;
        line-height: 1.8;
        margin-bottom: 20px;
    }

    .excel-features {
        list-style: none;
        padding: 0;
        margin: 0 0 24px;
    }

    .excel-features li {
        padding: 8px 0;
        border-bottom: 1px solid #f0f3f8;
        font-size: 13px;
        color: #52637b;
    }

    .excel-features li:last-child {
        border-bottom: none;
    }

    .excel-features li span {
        color: #16a36b;
        font-weight: 800;
        margin-right: 9px;
    }

    .excel-btn {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 9px;
        width: 100%;
        padding: 13px 18px;
        border-radius: 11px;
        font-weight: 700;
        font-size: 13px;
        text-decoration: none;
        border: none;
        transition: .2s ease;
    }

    .excel-btn:hover {
        transform: translateY(-2px);
        filter: brightness(.96);
    }

    .btn-export {
        background: #16a36b;
        color: white;
    }

    .btn-import {
        background: #3568ee;
        color: white;
    }

    .upload-area {
        border: 2px dashed #cbd7eb;
        border-radius: 14px;
        background: #f8faff;
        padding: 25px 16px;
        text-align: center;
        margin: 17px 0;
        transition: .2s ease;
    }

    .upload-area:hover {
        border-color: #3568ee;
        background: #f1f5ff;
    }

    .upload-symbol {
        font-size: 34px;
        margin-bottom: 10px;
    }

    .upload-area label {
        display: block;
        font-weight: 700;
        font-size: 13px;
        margin-bottom: 6px;
    }

    .upload-area small {
        color: #8b98aa;
        font-size: 11px;
    }

    .upload-area input {
        position: absolute;
        width: 1px;
        height: 1px;
        overflow: hidden;
        opacity: 0;
        pointer-events: none;
    }

    .upload-area.dragging {
        border-color: #3568ee;
        background: #edf3ff;
        transform: scale(1.01);
    }

    .upload-area.has-file {
        border-color: #16a36b;
        background: #f3fbf7;
    }

    .upload-picker {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-top: 12px;
        padding: 9px 13px;
        border-radius: 9px;
        background: white;
        border: 1px solid #dbe4f2;
        color: #3568ee;
        cursor: pointer;
    }

    .selected-file {
        display: none;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-top: 14px;
        padding: 11px 12px;
        border-radius: 10px;
        background: white;
        border: 1px solid #dfe7f3;
        text-align: left;
    }

    .selected-file.show {
        display: flex;
    }

    .selected-file-info {
        min-width: 0;
    }

    .selected-file strong,
    .selected-file small {
        display: block;
    }

    .selected-file strong {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 280px;
        font-size: 12px;
    }

    .selected-file small {
        color: #8b98aa;
        margin-top: 3px;
    }

    .remove-file {
        border: none;
        background: #fff1f2;
        color: #dc3545;
        border-radius: 8px;
        width: 32px;
        height: 32px;
        flex-shrink: 0;
    }

    .excel-btn:disabled {
        cursor: wait;
        opacity: .75;
        transform: none;
    }

    .excel-btn .spinner-border {
        width: 15px;
        height: 15px;
        border-width: 2px;
    }

    .guide-item {
        padding: 10px;
        border-radius: 11px;
        transition: .2s ease;
    }

    .guide-item:hover {
        background: #f7f9fd;
        transform: translateX(3px);
    }

    .excel-alert {
        animation: excelAlertIn .25s ease-out;
    }

    @keyframes excelAlertIn {
        from { opacity: 0; transform: translateY(-8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .excel-guide {
        background: white;
        border: 1px solid #e8edf5;
        border-radius: 18px;
        padding: 25px;
        box-shadow: 0 7px 24px rgba(30, 55, 90, .035);
    }

    .guide-title {
        font-weight: 800;
        font-size: 17px;
        margin-bottom: 20px;
    }

    .guide-list {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 20px;
    }

    .guide-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        color: #65758c;
        font-size: 12px;
        line-height: 1.6;
    }

    .guide-number {
        width: 28px;
        height: 28px;
        flex-shrink: 0;
        display: grid;
        place-items: center;
        background: #edf3ff;
        color: #3568ee;
        border-radius: 9px;
        font-weight: 800;
    }

    .excel-note {
        padding: 14px 16px;
        border-radius: 11px;
        background: #edf5ff;
        color: #466b9b;
        font-size: 12px;
        line-height: 1.7;
    }

    .excel-alert {
        border-radius: 12px;
        font-size: 13px;
        margin-bottom: 20px;
    }

    @media(max-width: 900px) {
        .excel-grid {
            grid-template-columns: 1fr;
        }

        .excel-stats {
            grid-template-columns: 1fr;
        }

        .guide-list {
            grid-template-columns: 1fr;
        }

        .excel-heading {
            align-items: flex-start;
            gap: 12px;
            flex-direction: column;
        }
    }
</style>

<div class="excel-page">

    <div class="excel-heading">
        <div>
            <h2>Import & Export Data</h2>
            <p>Kelola dan pindahkan data akademik Student Track dengan mudah.</p>
        </div>

        <div class="excel-date">
            📅 {{ now()->translatedFormat('d F Y') }}
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success excel-alert">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger excel-alert">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="excel-stats">

        <div class="excel-stat">
            <div class="stat-icon stat-blue">📊</div>
            <small>FORMAT FILE</small>
            <h3>XLSX / CSV</h3>
            <small>Format yang didukung sistem</small>
        </div>

        <div class="excel-stat">
            <div class="stat-icon stat-green">⬇️</div>
            <small>EXPORT DATA</small>
            <h3>Download</h3>
            <small>Unduh data nilai mahasiswa</small>
        </div>

        <div class="excel-stat">
            <div class="stat-icon stat-orange">⬆️</div>
            <small>IMPORT DATA</small>
            <h3>Upload</h3>
            <small>Tambahkan data nilai baru</small>
        </div>

    </div>

    <div class="excel-grid">

        <!-- EXPORT -->
        <div class="excel-card">

            <div class="excel-card-top">
                <div class="excel-icon stat-green">📤</div>
                <div>
                    <h4>Export Data Nilai</h4>
                    <p>Download academic records</p>
                </div>
            </div>

            <p class="excel-description">
                Ekspor seluruh riwayat penilaian mahasiswa
                dari database Student Track ke dalam file Excel.
            </p>

            <ul class="excel-features">
                <li><span>✓</span> NIM dan nama mahasiswa</li>
                <li><span>✓</span> Kode dan nama mata kuliah</li>
                <li><span>✓</span> SKS dan semester</li>
                <li><span>✓</span> Nilai tugas, UTS, UAS, praktikum</li>
                <li><span>✓</span> Tanggal penilaian</li>
            </ul>

            <a href="{{ route('excel.export') }}"
               class="excel-btn btn-export"
               id="exportButton">
                <span class="export-icon">⬇</span>
                <span class="export-text">Download File Excel</span>
            </a>

        </div>

        <!-- IMPORT -->
        <div class="excel-card">

            <div class="excel-card-top">
                <div class="excel-icon stat-blue">📥</div>
                <div>
                    <h4>Import Data Nilai</h4>
                    <p>Upload academic records</p>
                </div>
            </div>

            <p class="excel-description">
                Tambahkan data penilaian baru melalui file Excel
                dengan format yang sudah ditentukan.
            </p>

            <form action="{{ route('excel.import') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  id="importForm">

                @csrf

                <div class="upload-area" id="uploadArea">
                    <div class="upload-symbol">📁</div>

                    <label for="file_excel">Tarik file ke sini atau pilih file</label>

                    <small>
                        Pilih file XLSX, XLS, atau CSV maksimal 5 MB.
                    </small>

                    <input type="file"
                           name="file_excel"
                           id="file_excel"
                           accept=".xlsx,.xls,.csv"
                           required>

                    <label for="file_excel" class="upload-picker">
                        📂 Pilih File
                    </label>

                    <div class="selected-file" id="selectedFile">
                        <div class="selected-file-info">
                            <strong id="selectedFileName"></strong>
                            <small id="selectedFileSize"></small>
                        </div>
                        <button type="button"
                                class="remove-file"
                                id="removeFile"
                                aria-label="Hapus file">
                            ✕
                        </button>
                    </div>
                </div>

                <button type="submit" class="excel-btn btn-import" id="importButton">
                    <span class="import-icon">⬆</span>
                    <span class="import-text">Upload dan Import Data</span>
                </button>

            </form>

        </div>

    </div>

    <!-- PETUNJUK -->
    <div class="excel-guide">

        <div class="guide-title">
            📋 Panduan Penggunaan
        </div>

        <div class="guide-list">

            <div class="guide-item">
                <div class="guide-number">1</div>
                <div>
                    <strong>Unduh format Excel</strong><br>
                    Klik tombol Download untuk memperoleh format data.
                </div>
            </div>

            <div class="guide-item">
                <div class="guide-number">2</div>
                <div>
                    <strong>Isi data mahasiswa</strong><br>
                    Masukkan data sesuai susunan kolom yang tersedia.
                </div>
            </div>

            <div class="guide-item">
                <div class="guide-number">3</div>
                <div>
                    <strong>Periksa data</strong><br>
                    Pastikan nilai 0–100 dan tanggal valid.
                </div>
            </div>

            <div class="guide-item">
                <div class="guide-number">4</div>
                <div>
                    <strong>Upload file</strong><br>
                    Pilih file dan klik Upload dan Import Data.
                </div>
            </div>

        </div>

        <div class="excel-note">
            <strong>ⓘ Informasi penting</strong><br>
            Import akan menambahkan data baru ke database.
            Data yang sudah tersimpan tidak akan dihapus.
            Nilai akhir dan predikat dihitung otomatis oleh sistem.
        </div>

    </div>

</div>

<div class="modal fade" id="importConfirmModal" tabindex="-1" aria-labelledby="importConfirmLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border:0;border-radius:18px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="importConfirmLabel">Konfirmasi Import Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2">File yang dipilih:</p>
                <div class="p-3 rounded-3" style="background:#f5f8fd;">
                    <strong id="confirmFileName"></strong>
                    <small class="d-block text-secondary mt-1" id="confirmFileSize"></small>
                </div>
                <p class="mt-3 mb-0 text-secondary" style="font-size:13px;">
                    Data dari file akan ditambahkan ke database. Pastikan format dan isinya sudah benar.
                </p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmImportButton">
                    Ya, Import Sekarang
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fileInput = document.getElementById('file_excel');
    const uploadArea = document.getElementById('uploadArea');
    const selectedFile = document.getElementById('selectedFile');
    const selectedFileName = document.getElementById('selectedFileName');
    const selectedFileSize = document.getElementById('selectedFileSize');
    const removeFile = document.getElementById('removeFile');
    const importForm = document.getElementById('importForm');
    const importButton = document.getElementById('importButton');
    const confirmImportButton = document.getElementById('confirmImportButton');
    const confirmFileName = document.getElementById('confirmFileName');
    const confirmFileSize = document.getElementById('confirmFileSize');
    let confirmedImport = false;

    function formatBytes(bytes) {
        if (!bytes) return '0 Byte';
        const units = ['Byte', 'KB', 'MB'];
        const index = Math.min(Math.floor(Math.log(bytes) / Math.log(1024)), units.length - 1);
        return (bytes / Math.pow(1024, index)).toFixed(index === 0 ? 0 : 1) + ' ' + units[index];
    }

    function validateFile(file) {
        if (!file) return false;

        const allowedExtensions = ['xlsx', 'xls', 'csv'];
        const extension = file.name.split('.').pop().toLowerCase();

        if (!allowedExtensions.includes(extension)) {
            alert('Format file tidak didukung. Pilih file XLSX, XLS, atau CSV.');
            return false;
        }

        if (file.size > 5 * 1024 * 1024) {
            alert('Ukuran file melebihi batas maksimal 5 MB.');
            return false;
        }

        return true;
    }

    function showSelectedFile(file) {
        if (!validateFile(file)) {
            clearSelectedFile();
            return;
        }

        selectedFileName.textContent = file.name;
        selectedFileSize.textContent = formatBytes(file.size);
        confirmFileName.textContent = file.name;
        confirmFileSize.textContent = formatBytes(file.size);
        selectedFile.classList.add('show');
        uploadArea.classList.add('has-file');
    }

    function clearSelectedFile() {
        fileInput.value = '';
        selectedFile.classList.remove('show');
        uploadArea.classList.remove('has-file');
        selectedFileName.textContent = '';
        selectedFileSize.textContent = '';
        confirmedImport = false;
    }

    fileInput.addEventListener('change', function () {
        showSelectedFile(this.files[0]);
    });

    removeFile.addEventListener('click', clearSelectedFile);

    ['dragenter', 'dragover'].forEach(eventName => {
        uploadArea.addEventListener(eventName, function (event) {
            event.preventDefault();
            uploadArea.classList.add('dragging');
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, function (event) {
            event.preventDefault();
            uploadArea.classList.remove('dragging');
        });
    });

    uploadArea.addEventListener('drop', function (event) {
        const file = event.dataTransfer.files[0];
        if (!file) return;

        if (validateFile(file)) {
            const transfer = new DataTransfer();
            transfer.items.add(file);
            fileInput.files = transfer.files;
            showSelectedFile(file);
        } else {
            clearSelectedFile();
        }
    });

    importForm.addEventListener('submit', function (event) {
        if (confirmedImport) return;

        event.preventDefault();

        if (!fileInput.files[0]) {
            alert('Silakan pilih file Excel terlebih dahulu.');
            return;
        }

        if (!validateFile(fileInput.files[0])) {
            clearSelectedFile();
            return;
        }

        const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('importConfirmModal'));
        modal.show();
    });

    confirmImportButton.addEventListener('click', function () {
        confirmedImport = true;
        bootstrap.Modal.getInstance(document.getElementById('importConfirmModal')).hide();

        importButton.disabled = true;
        importButton.innerHTML =
            '<span class="spinner-border" aria-hidden="true"></span>' +
            '<span>Memproses import...</span>';

        importForm.submit();
    });

    const exportButton = document.getElementById('exportButton');
    exportButton.addEventListener('click', function () {
        exportButton.setAttribute('aria-disabled', 'true');
        exportButton.querySelector('.export-icon').textContent = '⏳';
        exportButton.querySelector('.export-text').textContent = 'Menyiapkan file...';

        window.setTimeout(function () {
            exportButton.removeAttribute('aria-disabled');
            exportButton.querySelector('.export-icon').textContent = '⬇';
            exportButton.querySelector('.export-text').textContent = 'Download File Excel';
        }, 4000);
    });
});
</script>
@endpush

@endsection
