<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreKelasRequest;
use App\Http\Requests\Admin\UpdateKelasRequest;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\NotifikasiAdminService;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $query = Kelas::with('dosen')->withCount('mahasiswa', 'tugas');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_kelas', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_kelas', 'like', '%' . $request->search . '%')
                  ->orWhere('mata_kuliah', 'like', '%' . $request->search . '%');
            });
        }

        $kelas = $query->orderBy('nama_kelas')->paginate(15)->withQueryString();

        return view('admin.kelas.index', compact('kelas'));
    }

    public function create()
    {
        $dosenList = User::where('role', 'dosen')->where('is_active', true)->orderBy('name')->get();
        return view('admin.kelas.create', compact('dosenList'));
    }

    public function store(StoreKelasRequest $request)
    {
        Kelas::create($request->validated());

        NotifikasiAdminService::kelasBaru(
            $kelas->nama_kelas,
            $kelas->kode_kelas,
            $kelas->semester
        );

        NotifikasiAdminService::success(
            'Kelas Baru Dibuat',
            "Kelas \"{$kelas->nama_kelas}\" ({$kelas->mata_kuliah}) berhasil dibuat.",
            'fa-school',
            route('admin.kelas.index')
        );

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kelas)
    {
        $dosenList    = User::where('role', 'dosen')->where('is_active', true)->orderBy('name')->get();
        $mahasiswaAll = User::where('role', 'mahasiswa')->where('is_active', true)->orderBy('name')->get();
        $kelas->load('mahasiswa');

        return view('admin.kelas.edit', compact('kelas', 'dosenList', 'mahasiswaAll'));
    }

    public function update(UpdateKelasRequest $request, Kelas $kelas)
    {
        $kelas->update($request->validated());

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kelas)
    {
        $kelas->delete();

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }

    public function assignDosen(Request $request, Kelas $kelas)
    {
        $request->validate([
            'dosen_id' => ['required', 'exists:users,id'],
        ]);

        $kelas->update(['dosen_id' => $request->dosen_id]);

        $dosen = \App\Models\User::find($request->dosen_id);
        NotifikasiAdminService::dosenDitugaskan($dosen->name, $kelas->nama_kelas);

        return redirect()->route('admin.kelas.edit', $kelas)
            ->with('success', 'Dosen pengampu berhasil diubah.');
    }

    public function assignMahasiswa(Request $request, Kelas $kelas)
    {
        $request->validate([
            'mahasiswa_ids'   => ['required', 'array'],
            'mahasiswa_ids.*' => ['exists:users,id'],
        ]);

        $kelas->mahasiswa()->syncWithoutDetaching($request->mahasiswa_ids);

        NotifikasiAdminService::mahasiswaDitambahkanKeKelas(
            count($request->mahasiswa_ids),
            $kelas->nama_kelas,
            $kelas->kode_kelas
        );

        return redirect()->route('admin.kelas.edit', $kelas)
            ->with('success', 'Mahasiswa berhasil ditambahkan ke kelas.');
    }

    public function removeMahasiswa(Kelas $kelas, User $mahasiswa)
    {
        $kelas->mahasiswa()->detach($mahasiswa->id);

        return redirect()->route('admin.kelas.edit', $kelas)
            ->with('success', 'Mahasiswa berhasil dikeluarkan dari kelas.');
    }
}