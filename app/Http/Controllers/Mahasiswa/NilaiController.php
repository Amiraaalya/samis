<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\PengumpulanTugas;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    public function index()
    {
        $pengumpulan = PengumpulanTugas::where('mahasiswa_id', Auth::id())
            ->with(['tugas.kelas', 'penilaian.dosen'])
            ->whereHas('penilaian')
            ->orderByDesc('submitted_at')
            ->paginate(15);

        $rataRata = PengumpulanTugas::where('mahasiswa_id', Auth::id())
            ->whereHas('penilaian')
            ->with('penilaian')
            ->get()
            ->avg(fn($p) => $p->penilaian->nilai);

        return view('mahasiswa.nilai.index', compact('pengumpulan', 'rataRata'));
    }
}