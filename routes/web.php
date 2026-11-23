<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\GrafikController;
use App\Http\Controllers\ExcelController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::get('/mata-kuliah', [MataKuliahController::class, 'index'])
    ->name('mata-kuliah.index');

Route::post('/mata-kuliah', [MataKuliahController::class, 'store'])
    ->name('mata-kuliah.store');

Route::delete('/mata-kuliah/{id}', [MataKuliahController::class, 'destroy'])
    ->name('mata-kuliah.destroy');

Route::get('/nilai', [NilaiController::class, 'index'])
    ->name('nilai.index');

Route::get('/nilai/tambah', [NilaiController::class, 'create'])
    ->name('nilai.create');

Route::post('/nilai', [NilaiController::class, 'store'])
    ->name('nilai.store');

Route::delete('/nilai/{id}', [NilaiController::class, 'destroy'])
    ->name('nilai.destroy');

Route::get('/tugas', [TugasController::class, 'index'])
    ->name('tugas.index');

Route::post('/tugas', [TugasController::class, 'store'])
    ->name('tugas.store');

Route::patch('/tugas/{id}/status', [TugasController::class, 'updateStatus'])
    ->name('tugas.status');

Route::delete('/tugas/{id}', [TugasController::class, 'destroy'])
    ->name('tugas.destroy');

Route::get('/grafik', [GrafikController::class, 'index'])
    ->name('grafik.index');

Route::get('/excel', [ExcelController::class, 'index'])
    ->name('excel.index');

Route::post('/excel/import', [ExcelController::class, 'import'])
    ->name('excel.import');

Route::get('/excel/export', [ExcelController::class, 'export'])
    ->name('excel.export');
