<?php

use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    // Siswa langsung diarahkan ke pemilihan kelas.
    return auth()->user()->isSiswa()
        ? redirect()->route('asesmen.start')
        : view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:siswa'])->prefix('asesmen')->name('asesmen.')->group(function () {
    Route::get('/', [AssessmentController::class, 'pilihKelas'])->name('start');
    Route::post('/mulai', [AssessmentController::class, 'mulai'])->name('mulai');

    Route::get('/{attempt}/soal', [AssessmentController::class, 'soal'])->name('soal');
    Route::post('/{attempt}/soal', [AssessmentController::class, 'jawab'])->name('jawab');
    Route::get('/{attempt}/analisis', [AssessmentController::class, 'analisis'])->name('analisis');
    Route::get('/{attempt}/pemetaan', [AssessmentController::class, 'pemetaan'])->name('pemetaan');
    Route::get('/{attempt}/verifikasi', [AssessmentController::class, 'verifikasi'])->name('verifikasi');
    Route::post('/{attempt}/verifikasi', [AssessmentController::class, 'verifikasiSimpan'])->name('verifikasi.simpan');
    Route::get('/{attempt}/remediasi', [AssessmentController::class, 'remediasi'])->name('remediasi');
    Route::post('/{attempt}/remediasi', [AssessmentController::class, 'remediasiSelesai'])->name('remediasi.selesai');
    Route::get('/{attempt}/uji', [AssessmentController::class, 'uji'])->name('uji');
});

require __DIR__.'/auth.php';