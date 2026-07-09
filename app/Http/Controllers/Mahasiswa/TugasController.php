<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TugasController extends Controller
{
    public function index(Request $request)
    {
        $kelasIds = Auth::user()->kelas()->pluck('kelas.id');

        $query = Tugas::whereIn('kelas_id', $kelasIds)
            ->where('jenis_tugas', 'kelas')
            ->with(['kelas', 'pengumpulan' => fn($q) => $q->where('mahasiswa_id', Auth::id())])
            ->withCount(['pengumpulan as sudah_dikumpulkan' => fn($q) => $q->where('mahasiswa_id', Auth::id())]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        $tugas = $query->orderBy('deadline')->paginate(15)->withQueryString();
        $kelas = Auth::user()->kelas()->orderBy('nama_kelas')->get();

        return view('mahasiswa.tugas.index', compact('tugas', 'kelas'));
    }

    public function show(Tugas $tugas)
    {
        $this->authorizeTugas($tugas);

        $tugas->load(['kelas', 'pembuat']);
        $pengumpulan = $tugas->pengumpulan()
            ->where('mahasiswa_id', Auth::id())
            ->with('penilaian')
            ->first();

        return view('mahasiswa.tugas.show', compact('tugas', 'pengumpulan'));
    }

    public function updateStatus(Request $request, Tugas $tugas)
    {
        $this->authorizeTugas($tugas);

        $request->validate([
            'status'  => ['required', 'in:belum_dikerjakan,sedang_dikerjakan,selesai'],
            'progres' => ['required', 'integer', 'in:0,25,50,75,100'],
        ]);

        if ($tugas->status === 'terlambat' && $request->status !== 'selesai') {
            return back()->with('error', 'Tugas sudah terlambat, hanya bisa ditandai selesai.');
        }

        $tugas->update([
            'status'  => $request->status,
            'progres' => $request->progres,
        ]);

        return back()->with('success', 'Status tugas berhasil diperbarui.');
    }

    private function authorizeTugas(Tugas $tugas): void
    {
        $kelasIds = Auth::user()->kelas()->pluck('kelas.id')->toArray();
        if (!in_array($tugas->kelas_id, $kelasIds)) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini.');
        }
    }
}