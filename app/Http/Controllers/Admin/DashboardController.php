<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AktivitasLog;
use App\Models\Kelas;
use App\Models\PengumpulanTugas;
use App\Models\Tugas;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'       => User::where('is_active', true)->count(),
            'total_dosen'       => User::where('role', 'dosen')->count(),
            'total_mahasiswa'   => User::where('role', 'mahasiswa')->count(),
            'total_kelas'       => Kelas::count(),
            'total_tugas'       => Tugas::count(),
            'total_pengumpulan' => PengumpulanTugas::count(),
        ];

        $aktivitasTerbaru = AktivitasLog::with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'aktivitasTerbaru'));
    }
}