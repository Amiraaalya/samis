<?php

namespace App\Services;

use App\Models\NotifikasiAdmin;
use App\Models\User;

class NotifikasiAdminService
{
    /**
     * Kirim notifikasi ke semua admin aktif.
     */
    public static function kirim(
        string $judul,
        string $pesan,
        string $tipe = 'info',
        string $icon = 'fa-bell',
        ?string $urlTujuan = null
    ): void {
        $adminIds = User::where('role', 'admin')
            ->where('is_active', true)
            ->pluck('id');

        foreach ($adminIds as $adminId) {
            NotifikasiAdmin::create([
                'user_id'    => $adminId,
                'judul'      => $judul,
                'pesan'      => $pesan,
                'tipe'       => $tipe,
                'icon'       => $icon,
                'url_tujuan' => $urlTujuan,
                'is_read'    => false,
            ]);
        }
    }

    // ── Shortcut tipe ─────────────────────────────────────────────────────

    public static function info(string $judul, string $pesan, string $icon = 'fa-info-circle', ?string $url = null): void
    {
        self::kirim($judul, $pesan, 'info', $icon, $url);
    }

    public static function success(string $judul, string $pesan, string $icon = 'fa-check-circle', ?string $url = null): void
    {
        self::kirim($judul, $pesan, 'success', $icon, $url);
    }

    public static function warning(string $judul, string $pesan, string $icon = 'fa-exclamation-triangle', ?string $url = null): void
    {
        self::kirim($judul, $pesan, 'warning', $icon, $url);
    }

    public static function danger(string $judul, string $pesan, string $icon = 'fa-times-circle', ?string $url = null): void
    {
        self::kirim($judul, $pesan, 'danger', $icon, $url);
    }

    // ── Event akademik spesifik ────────────────────────────────────────────

    public static function mahasiswaBaru(string $nama, string $nim): void
    {
        self::info(
            'Mahasiswa Baru Mendaftar',
            "{$nama} (NIM: {$nim}) baru saja mendaftar sebagai mahasiswa baru.",
            'fa-user-graduate',
            route('admin.users.index')
        );
    }

    public static function dosenBaru(string $nama, string $nip): void
    {
        self::success(
            'Akun Dosen Dibuat',
            "Admin berhasil membuat akun dosen untuk {$nama} (NIP: {$nip}).",
            'fa-chalkboard-teacher',
            route('admin.users.index')
        );
    }

    public static function kelasBaru(string $namaKelas, string $kodeKelas, string $semester): void
    {
        self::success(
            'Kelas Baru Dibuat',
            "Kelas \"{$namaKelas}\" ({$kodeKelas}) berhasil dibuat untuk semester {$semester}.",
            'fa-school',
            route('admin.kelas.index')
        );
    }

    public static function dosenDitugaskan(string $namaDosen, string $namaKelas): void
    {
        self::info(
            'Dosen Ditugaskan ke Kelas',
            "{$namaDosen} berhasil ditugaskan sebagai dosen pengampu kelas \"{$namaKelas}\".",
            'fa-user-tie',
            route('admin.kelas.index')
        );
    }

    public static function mahasiswaDitambahkanKeKelas(int $jumlah, string $namaKelas, string $kodeKelas): void
    {
        self::info(
            'Mahasiswa Ditambahkan ke Kelas',
            "{$jumlah} mahasiswa berhasil ditambahkan ke kelas \"{$namaKelas}\" ({$kodeKelas}).",
            'fa-users',
            route('admin.kelas.index')
        );
    }

    public static function tugasBaru(string $namaDosen, string $judulTugas, string $namaKelas, string $deadline): void
    {
        self::info(
            'Tugas Baru Dibuat',
            "{$namaDosen} membuat tugas baru \"{$judulTugas}\" di kelas {$namaKelas} dengan deadline {$deadline}.",
            'fa-tasks',
            route('admin.monitoring')
        );
    }

    public static function tugasDihapus(string $namaDosen, string $judulTugas, string $namaKelas): void
    {
        self::warning(
            'Tugas Dihapus',
            "{$namaDosen} menghapus tugas \"{$judulTugas}\" dari kelas {$namaKelas}.",
            'fa-trash',
            route('admin.monitoring')
        );
    }

    public static function mahasiswaTerlambat(int $jumlah, string $judulTugas, string $namaKelas): void
    {
        self::warning(
            'Mahasiswa Terlambat Mengumpulkan',
            "{$jumlah} mahasiswa di kelas {$namaKelas} belum mengumpulkan tugas \"{$judulTugas}\" yang sudah melewati deadline.",
            'fa-clock',
            route('admin.monitoring')
        );
    }

    public static function pengumumanBaru(string $isiPengumuman): void
    {
        self::info(
            'Pengumuman Baru Dibuat',
            "Admin telah membuat pengumuman baru: \"{$isiPengumuman}\".",
            'fa-bullhorn'
        );
    }

    public static function laporanAktivitas(string $deskripsi): void
    {
        self::info(
            'Laporan Aktivitas',
            $deskripsi,
            'fa-chart-bar',
            route('admin.monitoring')
        );
    }
}