<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {

       User::firstOrCreate(
            ['email' => 'admin@samis.ac.id'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'dosen1@samis.ac.id'],
            [
                'name' => 'Dr. Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'dosen',
                'nim_nip' => '198501012010011001',
                'is_active' => true,
            ]
        );

        User::firstOrCreate([
            'name'      => 'Dr. Sari Dewi',
            'email'     => 'dosen2@samis.ac.id',
            'password'  => Hash::make('password'),
            'role'      => 'dosen',
            'nim_nip'   => '199002022015012002',
            'is_active' => true,
        ]);

        $mahasiswa = [
            ['name' => 'Ahmad Fauzi',    'email' => 'mhs1@samis.ac.id', 'nim_nip' => '2021001001'],
            ['name' => 'Budi Prasetyo',  'email' => 'mhs2@samis.ac.id', 'nim_nip' => '2021001002'],
            ['name' => 'Citra Lestari',  'email' => 'mhs3@samis.ac.id', 'nim_nip' => '2021001003'],
            ['name' => 'Dewi Anggraini', 'email' => 'mhs4@samis.ac.id', 'nim_nip' => '2021001004'],
            ['name' => 'Eko Setiawan',   'email' => 'mhs5@samis.ac.id', 'nim_nip' => '2021001005'],
        ];

        foreach ($mahasiswa as $mhs) {
            User::create([
                'name'      => $mhs['name'],
                'email'     => $mhs['email'],
                'password'  => Hash::make('password'),
                'role'      => 'mahasiswa',
                'nim_nip'   => $mhs['nim_nip'],
                'is_active' => true,
            ]);
        }
    }
}