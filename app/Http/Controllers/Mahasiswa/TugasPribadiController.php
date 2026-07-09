<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mahasiswa\StoreTugasPribadiRequest;
use App\Models\Tugas;
use Illuminate\Support\Facades\Auth;

class TugasPribadiController extends Controller
{
    public function index()
    {
        $tugas = Tugas::where('user_id', Auth::id())
            ->where('jenis_tugas', 'pribadi')
            ->orderBy('deadline')
            ->paginate(15);

        return view('mahasiswa.tugas-pribadi.index', compact('tugas'));
    }

    public function create()
    {
        return view('mahasiswa.tugas-pribadi.create');
    }

    public function store(StoreTugasPribadiRequest $request)
    {
        Tugas::create([
            'kelas_id'   => null,
            'user_id'    => Auth::id(),
            'judul'      => $request->judul,
            'deskripsi'  => $request->deskripsi,
            'jenis_tugas'=> 'pribadi',
            'prioritas'  => $request->prioritas,
            'status'     => 'belum_dikerjakan',
            'progres'    => 0,
            'deadline'   => $request->deadline,
        ]);

        return redirect()->route('mahasiswa.tugas-pribadi.index')
            ->with('success', 'Tugas pribadi berhasil ditambahkan.');
    }

    public function edit(Tugas $tugasPribadi)
    {
        $this->authorize($tugasPribadi);
        return view('mahasiswa.tugas-pribadi.edit', ['tugas' => $tugasPribadi]);
    }

    public function update(StoreTugasPribadiRequest $request, Tugas $tugasPribadi)
    {
        $this->authorize($tugasPribadi);

        $tugasPribadi->update([
            'judul'     => $request->judul,
            'deskripsi' => $request->deskripsi,
            'prioritas' => $request->prioritas,
            'status'    => $request->status,
            'progres'   => $request->progres,
            'deadline'  => $request->deadline,
        ]);

        return redirect()->route('mahasiswa.tugas-pribadi.index')
            ->with('success', 'Tugas pribadi berhasil diperbarui.');
    }

    public function destroy(Tugas $tugasPribadi)
    {
        $this->authorize($tugasPribadi);
        $tugasPribadi->delete();

        return redirect()->route('mahasiswa.tugas-pribadi.index')
            ->with('success', 'Tugas pribadi berhasil dihapus.');
    }

    private function authorize(Tugas $tugas): void
    {
        if ($tugas->user_id !== Auth::id() || $tugas->jenis_tugas !== 'pribadi') {
            abort(403);
        }
    }
}