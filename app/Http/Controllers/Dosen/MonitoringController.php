<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\MahasiswaKelas;
use App\Models\PengumpulanTugas;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MonitoringController extends Controller
{
    public function index()
    {
        $kelas = Auth::user()->kelasYangDiampu()
            ->withCount(['mahasiswa', 'tugas'])
            ->get();

        return view('dosen.monitoring.index', compact('kelas'));
    }

    public function show(Kelas $kelas)
    {
        $this->authorizeKelas($kelas);

        $mahasiswa = $kelas->mahasiswa()
            ->with(['pengumpulan' => function ($q) use ($kelas) {
                $q->whereHas('tugas', fn($tq) => $tq->where('kelas_id', $kelas->id));
            }])
            ->get()
            ->map(function ($mhs) use ($kelas) {
                $totalTugas    = $kelas->tugas()->count();
                $tugasSelesai  = $mhs->pengumpulan->count();
                $tugasTerlambat= Tugas::where('kelas_id', $kelas->id)
                    ->where('status', 'terlambat')
                    ->whereDoesntHave('pengumpulan', fn($q) => $q->where('mahasiswa_id', $mhs->id))
                    ->count();

                $mhs->total_tugas     = $totalTugas;
                $mhs->tugas_selesai   = $tugasSelesai;
                $mhs->tugas_terlambat = $tugasTerlambat;
                $mhs->persentase      = $totalTugas > 0
                    ? round(($tugasSelesai / $totalTugas) * 100)
                    : 0;

                return $mhs;
            });

        $tugas = $kelas->tugas()->withCount('pengumpulan')->orderBy('deadline')->get();

        return view('dosen.monitoring.show', compact('kelas', 'mahasiswa', 'tugas'));
    }

    private function authorizeKelas(Kelas $kelas): void
    {
        if ($kelas->dosen_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke kelas ini.');
        }
    }
}