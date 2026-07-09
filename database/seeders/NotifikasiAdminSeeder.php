<?php

namespace Database\Seeders;

use App\Models\NotifikasiAdmin;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotifikasiAdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) return;

        NotifikasiAdmin::where('user_id', $admin->id)->delete();

        $data = [
            [
                'judul'      => 'Mahasiswa Baru Mendaftar',
                'pesan'      => 'Amira Alyana (NIM: 240102099) baru saja mendaftar sebagai mahasiswa baru.',
                'tipe'       => 'info',
                'icon'       => 'fa-user-graduate',
                'url_tujuan' => '/admin/users',
            ],
            [
                'judul'      => 'Akun Dosen Dibuat',
                'pesan'      => 'Admin berhasil membuat akun dosen untuk Dr. Fajar Mahardika, S.Kom., M.Kom (NIP: 198501012010011001).',
                'tipe'       => 'success',
                'icon'       => 'fa-chalkboard-teacher',
                'url_tujuan' => '/admin/users',
            ],
            [
                'judul'      => 'Kelas Baru Dibuat',
                'pesan'      => 'Kelas "Praktikum Pemrograman Framework" (PBF-TI2D) berhasil dibuat untuk semester Genap 2025/2026.',
                'tipe'       => 'success',
                'icon'       => 'fa-school',
                'url_tujuan' => '/admin/kelas',
            ],
            [
                'judul'      => 'Dosen Ditugaskan ke Kelas',
                'pesan'      => 'Dr. Fajar Mahardika berhasil ditugaskan sebagai dosen pengampu kelas "Praktikum Pemrograman Framework".',
                'tipe'       => 'info',
                'icon'       => 'fa-user-tie',
                'url_tujuan' => '/admin/kelas',
            ],
            [
                'judul'      => 'Mahasiswa Ditambahkan ke Kelas',
                'pesan'      => '6 mahasiswa berhasil ditambahkan ke kelas "Praktikum Pemrograman Framework" (PBF-TI2D).',
                'tipe'       => 'info',
                'icon'       => 'fa-users',
                'url_tujuan' => '/admin/kelas',
            ],
            [
                'judul'      => 'Tugas Baru Dibuat',
                'pesan'      => 'Dr. Fajar Mahardika membuat tugas baru "Tugas Projek Framework" di kelas TI2D dengan deadline 12 Jul 2026.',
                'tipe'       => 'info',
                'icon'       => 'fa-tasks',
                'url_tujuan' => '/admin/monitoring',
            ],
            [
                'judul'      => 'Mahasiswa Terlambat Mengumpulkan',
                'pesan'      => '3 mahasiswa di kelas TI2D belum mengumpulkan tugas "Normalisasi Tabel Database" yang sudah melewati deadline.',
                'tipe'       => 'warning',
                'icon'       => 'fa-clock',
                'url_tujuan' => '/admin/monitoring',
            ],
            [
                'judul'      => 'Pengumuman Baru',
                'pesan'      => 'Admin telah membuat pengumuman baru: "Jadwal UAS Semester Genap 2025/2026 telah dirilis."',
                'tipe'       => 'info',
                'icon'       => 'fa-bullhorn',
                'url_tujuan' => null,
            ],
        ];

        foreach ($data as $item) {
            NotifikasiAdmin::create([
                'user_id' => $admin->id,
                'is_read' => false,
                ...$item,
            ]);
        }
    }
}