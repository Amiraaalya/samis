<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Tugas;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index()
    {
        $rekapTugas = Tugas::with(['kelas', 'pembuat'])
            ->withCount('pengumpulan')
            ->orderBy('deadline', 'desc')
            ->paginate(20);

        $rekapMahasiswa = User::where('role', 'mahasiswa')
            ->withCount(['pengumpulan', 'kelas'])
            ->orderBy('name')
            ->paginate(20);

        return view('admin.laporan.index', compact('rekapTugas', 'rekapMahasiswa'));
    }

    public function exportPdf()
    {
        $tugas = Tugas::with(['kelas', 'pembuat'])->withCount('pengumpulan')->get();
        $mahasiswa = User::where('role', 'mahasiswa')->withCount(['pengumpulan', 'kelas'])->get();
        $kelas     = Kelas::with('dosen')->withCount(['mahasiswa', 'tugas'])->get();

        $pdf = Pdf::loadView('admin.laporan.pdf', compact('tugas', 'mahasiswa', 'kelas'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-samis-' . now()->format('Y-m-d') . '.pdf');
    }
}