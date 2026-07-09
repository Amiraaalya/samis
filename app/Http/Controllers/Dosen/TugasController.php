<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dosen\StoreTugasRequest;
use App\Http\Requests\Dosen\UpdateTugasRequest;
use App\Models\AktivitasLog;
use App\Models\Kelas;
use App\Models\Tugas;
use Illuminate\Support\Facades\Auth;
use App\Services\NotifikasiAdminService;

class TugasController extends Controller
{
    public function index()
    {
        $kelasIds = Auth::user()->kelasYangDiampu()->pluck('id');

        $tugas = Tugas::whereIn('kelas_id', $kelasIds)
            ->with(['kelas', 'pembuat'])
            ->withCount('pengumpulan')
            ->orderBy('deadline')
            ->paginate(15);

        return view('dosen.tugas.index', compact('tugas'));
    }

    public function create()
    {
        $kelasList = Auth::user()->kelasYangDiampu()->orderBy('nama_kelas')->get();
        return view('dosen.tugas.create', compact('kelasList'));
    }

    public function store(StoreTugasRequest $request)
    {
        $tugas = Tugas::create([
            'kelas_id'   => $request->kelas_id,
            'user_id'    => Auth::id(),
            'judul'      => $request->judul,
            'deskripsi'  => $request->deskripsi,
            'jenis_tugas'=> 'kelas',
            'prioritas'  => $request->prioritas,
            'status'     => 'belum_dikerjakan',
            'progres'    => 0,
            'deadline'   => $request->deadline,
        ]);

        NotifikasiAdminService::info(
            'Tugas Baru Dibuat',
            "Dosen {$request->user()->name} membuat tugas \"{$tugas->judul}\".",
            'fa-tasks',
            route('admin.monitoring')
        );

        NotifikasiAdminService::tugasBaru(
            Auth::user()->name,
            $tugas->judul,
            $tugas->kelas->nama_kelas ?? '-',
            $tugas->deadline->format('d M Y')
        );

        AktivitasLog::create([
            'user_id'    => Auth::id(),
            'aksi'       => 'create_tugas',
            'model_type' => Tugas::class,
            'model_id'   => $tugas->id,
            'data_baru'  => $tugas->toArray(),
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('dosen.tugas.index')
            ->with('success', 'Tugas berhasil dibuat.');
    }

    public function edit(Tugas $tugas)
    {
        $this->authorizeTugas($tugas);
        $kelasList = Auth::user()->kelasYangDiampu()->orderBy('nama_kelas')->get();

        return view('dosen.tugas.edit', compact('tugas', 'kelasList'));
    }

    public function update(UpdateTugasRequest $request, Tugas $tugas)
    {
        $this->authorizeTugas($tugas);
        $dataLama = $tugas->toArray();

        $tugas->update([
            'kelas_id'  => $request->kelas_id,
            'judul'     => $request->judul,
            'deskripsi' => $request->deskripsi,
            'prioritas' => $request->prioritas,
            'deadline'  => $request->deadline,
        ]);

        AktivitasLog::create([
            'user_id'    => Auth::id(),
            'aksi'       => 'update_tugas',
            'model_type' => Tugas::class,
            'model_id'   => $tugas->id,
            'data_lama'  => $dataLama,
            'data_baru'  => $tugas->fresh()->toArray(),
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('dosen.tugas.index')
            ->with('success', 'Tugas berhasil diperbarui.');
    }

    public function destroy(Tugas $tugas)
    {
        $this->authorizeTugas($tugas);

        AktivitasLog::create([
            'user_id'    => Auth::id(),
            'aksi'       => 'delete_tugas',
            'model_type' => Tugas::class,
            'model_id'   => $tugas->id,
            'data_lama'  => $tugas->toArray(),
            'ip_address' => request()->ip(),
        ]);

        NotifikasiAdminService::warning(
            'Tugas Dihapus',
            "Dosen " . auth()->user()->name . " menghapus tugas \"{$tugas->judul}\".",
            'fa-trash',
            route('admin.monitoring')
        );

        NotifikasiAdminService::tugasDihapus(
            Auth::user()->name,
            $tugas->judul,
            $tugas->kelas->nama_kelas ?? '-'
        );

        $tugas->delete();

        return redirect()->route('dosen.tugas.index')
            ->with('success', 'Tugas berhasil dihapus.');
    }

    private function authorizeTugas(Tugas $tugas): void
    {
        $kelasIds = Auth::user()->kelasYangDiampu()->pluck('id')->toArray();
        if (!in_array($tugas->kelas_id, $kelasIds)) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini.');
        }
    }
}