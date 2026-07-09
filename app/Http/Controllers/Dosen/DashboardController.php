<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $dosen       = Auth::user();
        $kelasIds    = $dosen->kelasYangDiampu()->pluck('id');

        $totalKelas     = $kelasIds->count();
        $totalTugas     = Tugas::whereIn('kelas_id', $kelasIds)->count();
        $totalMahasiswa = \App\Models\MahasiswaKelas::whereIn('kelas_id', $kelasIds)
                            ->distinct('mahasiswa_id')->count('mahasiswa_id');

        $tugasAktif = Tugas::whereIn('kelas_id', $kelasIds)
            ->whereNotIn('status', ['selesai', 'terlambat'])
            ->with('kelas')
            ->orderBy('deadline')
            ->take(5)
            ->get();

        $statusChart = [
            'belum_dikerjakan'  => Tugas::whereIn('kelas_id', $kelasIds)->where('status', 'belum_dikerjakan')->count(),
            'sedang_dikerjakan' => Tugas::whereIn('kelas_id', $kelasIds)->where('status', 'sedang_dikerjakan')->count(),
            'selesai'           => Tugas::whereIn('kelas_id', $kelasIds)->where('status', 'selesai')->count(),
            'terlambat'         => Tugas::whereIn('kelas_id', $kelasIds)->where('status', 'terlambat')->count(),
        ];

        $kelas = $dosen->kelasYangDiampu()->withCount(['mahasiswa', 'tugas'])->get();

        return view('dosen.dashboard', compact(
            'totalKelas', 'totalTugas', 'totalMahasiswa',
            'tugasAktif', 'statusChart', 'kelas'
        ));
    }
}