<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dosen\StorePenilaianRequest;
use App\Models\Notifikasi;
use App\Models\Penilaian;
use App\Models\PengumpulanTugas;
use Illuminate\Support\Facades\Auth;

class PenilaianController extends Controller
{
    public function index()
    {
        $kelasIds = Auth::user()->kelasYangDiampu()->pluck('id');

        $pengumpulan = PengumpulanTugas::whereHas('tugas', fn($q) => $q->whereIn('kelas_id', $kelasIds))
            ->with(['tugas.kelas', 'mahasiswa', 'penilaian'])
            ->orderByDesc('submitted_at')
            ->paginate(15);

        return view('dosen.penilaian.index', compact('pengumpulan'));
    }

    public function show(PengumpulanTugas $pengumpulan)
    {
        $this->authorizePengumpulan($pengumpulan);
        $pengumpulan->load(['tugas.kelas', 'mahasiswa', 'penilaian.dosen']);

        return view('dosen.penilaian.show', compact('pengumpulan'));
    }

    public function store(StorePenilaianRequest $request, PengumpulanTugas $pengumpulan)
    {
        $this->authorizePengumpulan($pengumpulan);

        Penilaian::create([
            'pengumpulan_id' => $pengumpulan->id,
            'dosen_id'       => Auth::id(),
            'nilai'          => $request->nilai,
            'feedback'       => $request->feedback,
            'dinilai_at'     => now(),
        ]);

        $pengumpulan->tugas->update(['status' => 'selesai']);

        // Notifikasi ke mahasiswa
        $judul       = $pengumpulan->tugas->judul;
        $nilai       = $request->nilai;
        $gradeLabel  = $this->getGradeLabel($nilai);
        $dosenNama   = Auth::user()->name;

        Notifikasi::firstOrCreate(
            [
                'user_id'       => $pengumpulan->mahasiswa_id,
                'tugas_id'      => $pengumpulan->tugas_id,
                'jenis'         => 'selesai',
                'tanggal_kirim' => today()->toDateString(),
            ],
            [
                'pesan'   => "Tugas \"{$judul}\" telah dinilai oleh {$dosenNama}. Nilai Anda: {$nilai} ({$gradeLabel}).",
                'is_read' => false,
            ]
        );

        return redirect()->route('dosen.penilaian.index')
            ->with('success', 'Penilaian berhasil disimpan.');
    }

    public function update(StorePenilaianRequest $request, Penilaian $penilaian)
    {
        $this->authorizePenilaian($penilaian);

        $nilaiLama = $penilaian->nilai;

        $penilaian->update([
            'nilai'      => $request->nilai,
            'feedback'   => $request->feedback,
            'dinilai_at' => now(),
        ]);

        // Notifikasi ke mahasiswa bahwa nilai diperbarui
        $pengumpulan = $penilaian->pengumpulan;
        $judul       = $pengumpulan->tugas->judul;
        $nilai       = $request->nilai;
        $gradeLabel  = $this->getGradeLabel($nilai);
        $dosenNama   = Auth::user()->name;

        Notifikasi::create([
            'user_id'       => $pengumpulan->mahasiswa_id,
            'tugas_id'      => $pengumpulan->tugas_id,
            'jenis'         => 'selesai',
            'pesan'         => "Nilai tugas \"{$judul}\" diperbarui oleh {$dosenNama}. Nilai baru Anda: {$nilai} ({$gradeLabel}).",
            'is_read'       => false,
            'tanggal_kirim' => today()->toDateString(),
        ]);

        return redirect()->route('dosen.penilaian.index')
            ->with('success', 'Nilai berhasil diperbarui.');
    }

    public function download(PengumpulanTugas $pengumpulan)
    {
        $this->authorizePengumpulan($pengumpulan);

        if (!\Storage::disk('local')->exists($pengumpulan->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return \Storage::disk('local')->download(
            $pengumpulan->file_path,
            $pengumpulan->file_name
        );
    }

    private function authorizePengumpulan(PengumpulanTugas $pengumpulan): void
    {
        $kelasIds = Auth::user()->kelasYangDiampu()->pluck('id')->toArray();
        if (!in_array($pengumpulan->tugas->kelas_id, $kelasIds)) {
            abort(403);
        }
    }

    private function authorizePenilaian(Penilaian $penilaian): void
    {
        if ($penilaian->dosen_id !== Auth::id()) {
            abort(403);
        }
    }

    private function getGradeLabel(float $nilai): string
    {
        return match(true) {
            $nilai >= 85 => 'A',
            $nilai >= 75 => 'B',
            $nilai >= 65 => 'C',
            $nilai >= 55 => 'D',
            default      => 'E',
        };
    }
}