<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\User;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $dosen1 = User::where('email', 'dosen1@samis.ac.id')->first();
        $dosen2 = User::where('email', 'dosen2@samis.ac.id')->first();

        $kelas1 = Kelas::create([
            'nama_kelas'  => 'Pemrograman Web A',
            'kode_kelas'  => 'PWA-2025',
            'mata_kuliah' => 'Pemrograman Web',
            'semester'    => 'Genap 2024/2025',
            'dosen_id'    => $dosen1->id,
        ]);

        $kelas2 = Kelas::create([
            'nama_kelas'  => 'Basis Data B',
            'kode_kelas'  => 'BDB-2025',
            'mata_kuliah' => 'Basis Data',
            'semester'    => 'Genap 2024/2025',
            'dosen_id'    => $dosen2->id,
        ]);

        $mahasiswaIds = User::where('role', 'mahasiswa')
                            ->pluck('id')
                            ->toArray();

        $kelas1->mahasiswa()->attach($mahasiswaIds);

        $kelas2->mahasiswa()->attach(array_slice($mahasiswaIds, 0, 3));
    }
}