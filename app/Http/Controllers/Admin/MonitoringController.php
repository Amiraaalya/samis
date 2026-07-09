<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AktivitasLog;
use App\Models\Kelas;
use App\Models\PengumpulanTugas;
use App\Models\Tugas;
use App\Models\User;

class MonitoringController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'       => User::count(),
            'total_kelas'       => Kelas::count(),
            'total_tugas'       => Tugas::count(),
            'total_pengumpulan' => PengumpulanTugas::count(),
            'tugas_terlambat'   => Tugas::where('status', 'terlambat')->count(),
            'tugas_selesai'     => Tugas::where('status', 'selesai')->count(),
        ];

        $statusChart = [
            'belum_dikerjakan'  => Tugas::where('status', 'belum_dikerjakan')->count(),
            'sedang_dikerjakan' => Tugas::where('status', 'sedang_dikerjakan')->count(),
            'selesai'           => Tugas::where('status', 'selesai')->count(),
            'terlambat'         => Tugas::where('status', 'terlambat')->count(),
        ];

        $aktivitasTerbaru = AktivitasLog::with('user')
            ->latest()
            ->take(20)
            ->get();

        $kelasData = Kelas::with('dosen')
            ->withCount(['mahasiswa', 'tugas'])
            ->orderBy('nama_kelas')
            ->get();

        return view('admin.monitoring.index', compact(
            'stats', 'statusChart', 'aktivitasTerbaru', 'kelasData'
        ));
    }
}