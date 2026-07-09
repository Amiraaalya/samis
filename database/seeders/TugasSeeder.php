<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class TugasSeeder extends Seeder
{
    public function run(): void
    {
        $dosen1 = User::where('email', 'dosen1@samis.ac.id')->first();
        $dosen2 = User::where('email', 'dosen2@samis.ac.id')->first();
        $kelas1 = Kelas::where('kode_kelas', 'PWA-2025')->first();
        $kelas2 = Kelas::where('kode_kelas', 'BDB-2025')->first();
        $mhs1   = User::where('email', 'mhs1@samis.ac.id')->first();

        Tugas::create([
            'kelas_id'   => $kelas1->id,
            'user_id'    => $dosen1->id,
            'judul'      => 'Membuat Form Login dengan Laravel',
            'deskripsi'  => 'Buat form login menggunakan Laravel Breeze dengan validasi.',
            'jenis_tugas'=> 'kelas',
            'prioritas'  => 'tinggi',
            'status'     => 'belum_dikerjakan',
            'progres'    => 0,
            'deadline'   => Carbon::now()->addDays(7),
        ]);

        Tugas::create([
            'kelas_id'   => $kelas1->id,
            'user_id'    => $dosen1->id,
            'judul'      => 'Implementasi CRUD Produk',
            'deskripsi'  => 'Buat CRUD produk dengan resource controller.',
            'jenis_tugas'=> 'kelas',
            'prioritas'  => 'sedang',
            'status'     => 'sedang_dikerjakan',
            'progres'    => 50,
            'deadline'   => Carbon::now()->addDays(3),
        ]);

        Tugas::create([
            'kelas_id'   => $kelas2->id,
            'user_id'    => $dosen2->id,
            'judul'      => 'Normalisasi Tabel Database',
            'deskripsi'  => 'Normalisasi hingga 3NF untuk studi kasus toko online.',
            'jenis_tugas'=> 'kelas',
            'prioritas'  => 'tinggi',
            'status'     => 'belum_dikerjakan',
            'progres'    => 0,
            'deadline'   => Carbon::now()->addDays(14),
        ]);

        Tugas::create([
            'kelas_id'   => null,
            'user_id'    => $mhs1->id,
            'judul'      => 'Belajar Tailwind CSS',
            'deskripsi'  => 'Pelajari dokumentasi Tailwind CSS v3.',
            'jenis_tugas'=> 'pribadi',
            'prioritas'  => 'rendah',
            'status'     => 'sedang_dikerjakan',
            'progres'    => 25,
            'deadline'   => Carbon::now()->addDays(10),
        ]);
    }
}