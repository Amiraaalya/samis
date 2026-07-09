<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user     = Auth::user();
        $kelasIds = $user->kelas()->pluck('kelas.id');

        $tugasKelas = Tugas::whereIn('kelas_id', $kelasIds)->where('jenis_tugas', 'kelas');
        $tugasPribadi = Tugas::where('user_id', $user->id)->where('jenis_tugas', 'pribadi');

        $stats = [
            'total_tugas'       => $tugasKelas->count() + $tugasPribadi->count(),
            'tugas_selesai'     => (clone $tugasKelas)->where('status', 'selesai')->count()
                                 + (clone $tugasPribadi)->where('status', 'selesai')->count(),
            'tugas_belum'       => (clone $tugasKelas)->where('status', 'belum_dikerjakan')->count()
                                 + (clone $tugasPribadi)->where('status', 'belum_dikerjakan')->count(),
            'tugas_terlambat'   => (clone $tugasKelas)->where('status', 'terlambat')->count()
                                 + (clone $tugasPribadi)->where('status', 'terlambat')->count(),
        ];

        $deadlineTerdekat = Tugas::whereIn('kelas_id', $kelasIds)
            ->where('jenis_tugas', 'kelas')
            ->whereNotIn('status', ['selesai'])
            ->where('deadline', '>=', now())
            ->orderBy('deadline')
            ->first();

        $tugasUntukKalender = Tugas::whereIn('kelas_id', $kelasIds)
            ->where('jenis_tugas', 'kelas')
            ->whereNotIn('status', ['selesai'])
            ->select('id', 'judul', 'deadline', 'status', 'prioritas')
            ->get()
            ->map(fn($t) => [
                'id'    => $t->id,
                'title' => $t->judul,
                'start' => $t->deadline->toDateString(),
                'color' => match($t->prioritas) {
                    'tinggi' => '#EF4444',
                    'sedang' => '#F59E0B',
                    default  => '#10B981',
                },
                'url' => route('mahasiswa.tugas.show', $t->id),
            ]);

        $notifikasiUnread = $user->notifikasiUnread()->with('tugas')->latest()->take(5)->get();

        $statusChart = [
            'labels' => ['Belum Dikerjakan', 'Sedang Dikerjakan', 'Selesai', 'Terlambat'],
            'data'   => [
                $stats['tugas_belum'],
                (clone $tugasKelas)->where('status', 'sedang_dikerjakan')->count()
                    + (clone $tugasPribadi)->where('status', 'sedang_dikerjakan')->count(),
                $stats['tugas_selesai'],
                $stats['tugas_terlambat'],
            ],
        ];

        return view('mahasiswa.dashboard', compact(
            'stats', 'deadlineTerdekat', 'tugasUntukKalender',
            'notifikasiUnread', 'statusChart'
        ));
    }
}