<?php

use App\Http\Controllers\Auth\RedirectAfterLogin;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Dosen;
use App\Http\Controllers\Mahasiswa;
use App\Http\Controllers\NotifikasiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\NotifikasiAdminController;

// ─── Landing Page ─────────────────────────────────────────────────────────────
Route::get('/', function () {
    // Kalau sudah login, langsung redirect ke dashboard sesuai role
    if (auth()->check()) {
        return match(auth()->user()->role) {
            'admin'     => redirect()->route('admin.dashboard'),
            'dosen'     => redirect()->route('dosen.dashboard'),
            'mahasiswa' => redirect()->route('mahasiswa.dashboard'),
            default     => redirect()->route('login'),
        };
    }

    return view('welcome');
})->name('home');

// Auth routes (Laravel Breeze)
require __DIR__.'/auth.php';

// Post-login redirect berdasarkan role
Route::get('/dashboard', RedirectAfterLogin::class)
    ->middleware(['auth'])
    ->name('dashboard');

// ─── Admin Routes ────────────────────────────────────────────────────────────
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('users', Admin\UserController::class);

        Route::resource('kelas', Admin\KelasController::class)
            ->parameters(['kelas' => 'kelas']);
        Route::post('kelas/{kelas}/assign-dosen',
            [Admin\KelasController::class, 'assignDosen'])->name('kelas.assignDosen');
        Route::post('kelas/{kelas}/assign-mahasiswa',
            [Admin\KelasController::class, 'assignMahasiswa'])->name('kelas.assignMahasiswa');
        Route::delete('kelas/{kelas}/remove-mahasiswa/{mahasiswa}',
            [Admin\KelasController::class, 'removeMahasiswa'])->name('kelas.removeMahasiswa');

        Route::get('/monitoring', [Admin\MonitoringController::class, 'index'])
            ->name('monitoring');
        Route::get('/laporan', [Admin\LaporanController::class, 'index'])
            ->name('laporan');
        Route::get('/laporan/export-pdf', [Admin\LaporanController::class, 'exportPdf'])
            ->name('laporan.exportPdf');

        // Notifikasi Admin
        Route::get('/notifikasi', [Admin\NotifikasiAdminController::class, 'index'])->name('notifikasi.index');
        Route::patch('/notifikasi/{notifikasi}/read', [Admin\NotifikasiAdminController::class, 'markRead'])->name('notifikasi.markRead');
        Route::patch('/notifikasi/read-all', [Admin\NotifikasiAdminController::class, 'markAllRead'])->name('notifikasi.markAllRead');
        Route::delete('/notifikasi/{notifikasi}', [Admin\NotifikasiAdminController::class, 'destroy'])->name('notifikasi.destroy');
        Route::delete('/notifikasi', [Admin\NotifikasiAdminController::class, 'destroyAll'])->name('notifikasi.destroyAll');
        Route::get('/notifikasi/unread-count', [Admin\NotifikasiAdminController::class, 'getUnreadCount'])->name('notifikasi.unreadCount');
    });

// ─── Dosen Routes ────────────────────────────────────────────────────────────
Route::prefix('dosen')
    ->name('dosen.')
    ->middleware(['auth', 'role:dosen'])
    ->group(function () {

        Route::get('/dashboard', [Dosen\DashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('tugas', Dosen\TugasController::class)
            ->parameters(['tugas' => 'tugas']);

        Route::get('/monitoring', [Dosen\MonitoringController::class, 'index'])
            ->name('monitoring');
        Route::get('/monitoring/{kelas}', [Dosen\MonitoringController::class, 'show'])
            ->name('monitoring.show');

        Route::get('/penilaian', [Dosen\PenilaianController::class, 'index'])
            ->name('penilaian.index');
        Route::get('/penilaian/{pengumpulan}', [Dosen\PenilaianController::class, 'show'])
            ->name('penilaian.show');
        Route::post('/penilaian/{pengumpulan}', [Dosen\PenilaianController::class, 'store'])
            ->name('penilaian.store');
        Route::put('/penilaian/{penilaian}', [Dosen\PenilaianController::class, 'update'])
            ->name('penilaian.update');
        Route::get('/penilaian/{pengumpulan}/download', [Dosen\PenilaianController::class, 'download'])
            ->name('penilaian.download');
    });

// ─── Mahasiswa Routes ─────────────────────────────────────────────────────────
Route::prefix('mahasiswa')
    ->name('mahasiswa.')
    ->middleware(['auth', 'role:mahasiswa'])
    ->group(function () {

        Route::get('/dashboard', [Mahasiswa\DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/tugas', [Mahasiswa\TugasController::class, 'index'])
            ->name('tugas.index');
        Route::get('/tugas/{tugas}', [Mahasiswa\TugasController::class, 'show'])
            ->name('tugas.show');
        Route::patch('/tugas/{tugas}/status', [Mahasiswa\TugasController::class, 'updateStatus'])
            ->name('tugas.updateStatus');

        Route::resource('tugas-pribadi', Mahasiswa\TugasPribadiController::class)
            ->except(['show']);

        Route::post('/pengumpulan/{tugas}', [Mahasiswa\PengumpulanController::class, 'store'])
            ->name('pengumpulan.store');
        Route::post('/pengumpulan/{pengumpulan}/update', [Mahasiswa\PengumpulanController::class, 'update'])
            ->name('pengumpulan.update');
        Route::get('/pengumpulan/{pengumpulan}/download',[Mahasiswa\PengumpulanController::class, 'download'])
            ->name('pengumpulan.download');

        Route::get('/nilai', [Mahasiswa\NilaiController::class, 'index'])
            ->name('nilai.index');
    });

// ─── Notifikasi (semua role) ──────────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::patch('/notifikasi/{notifikasi}/read', [NotifikasiController::class, 'markRead'])->name('notifikasi.markRead');
    Route::patch('/notifikasi/read-all', [NotifikasiController::class, 'markAllRead'])->name('notifikasi.markAllRead');
    Route::delete('/notifikasi/{notifikasi}', [NotifikasiController::class, 'destroy'])->name('notifikasi.destroy');
    Route::delete('/notifikasi', [NotifikasiController::class, 'destroyAll'])->name('notifikasi.destroyAll');
});

// ─── Profil (Dosen & Mahasiswa) ───────────────────────────────────────────────
Route::middleware(['auth', 'role:dosen,mahasiswa'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'removeAvatar'])->name('profile.removeAvatar');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
});