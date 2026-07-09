<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\PengumpulanTugas;
use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengumpulanController extends Controller
{
    public function store(Request $request, Tugas $tugas)
    {
        $this->authorizeTugas($tugas);

        $request->validate([
            'file'    => ['required', 'file', 'max:51200', 'mimes:pdf,doc,docx,zip,rar,png,jpg,jpeg'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ], [
            'file.required' => 'File tugas wajib dipilih.',
            'file.max'      => 'Ukuran file maksimal 50MB.',
            'file.mimes'    => 'Format file harus PDF, DOC, DOCX, ZIP, RAR, PNG, atau JPG.',
        ]);

        $existing = PengumpulanTugas::where('tugas_id', $tugas->id)
            ->where('mahasiswa_id', Auth::id())
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah mengumpulkan tugas ini. Gunakan fitur update file.');
        }

        $file = $request->file('file');
        $path = $file->store(
            'submissions/' . $tugas->kelas_id . '/' . $tugas->id . '/' . Auth::id(),
            'local'
        );

        PengumpulanTugas::create([
            'tugas_id'     => $tugas->id,
            'mahasiswa_id' => Auth::id(),
            'file_path'    => $path,
            'file_name'    => $file->getClientOriginalName(),
            'file_size'    => $file->getSize(),
            'mime_type'    => $file->getMimeType(),
            'catatan'      => $request->catatan,
            'submitted_at' => now(),
        ]);
        $tugas->load('kelas');

        // Notifikasi ke dosen pengampu
        $mahasiswaNama = Auth::user()->name;
        \App\Models\Notifikasi::firstOrCreate(
            [
                'user_id'       => $tugas->kelas->dosen_id,
                'tugas_id'      => $tugas->id,
                'jenis'         => 'h1',
                'tanggal_kirim' => today()->toDateString(),
            ],
            [
                'pesan'   => "{$mahasiswaNama} telah mengumpulkan tugas \"{$tugas->judul}\".",
                'is_read' => false,
            ]
        );

        $tugas->update(['status' => 'selesai', 'progres' => 100]);

        return back()->with('success', 'Tugas berhasil dikumpulkan.');
    }

    public function update(Request $request, PengumpulanTugas $pengumpulan)
    {
        if ($pengumpulan->mahasiswa_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'file'    => ['required', 'file', 'max:51200', 'mimes:pdf,doc,docx,zip,rar,png,jpg,jpeg'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ], [
            'file.required' => 'File tugas wajib dipilih.',
            'file.max'      => 'Ukuran file maksimal 50MB.',
            'file.mimes'    => 'Format file harus PDF, DOC, DOCX, ZIP, RAR, PNG, atau JPG.',
        ]);

        Storage::disk('local')->delete($pengumpulan->file_path);

        $file  = $request->file('file');
        $tugas = $pengumpulan->tugas;
        $path  = $file->store(
            'submissions/' . $tugas->kelas_id . '/' . $tugas->id . '/' . Auth::id(),
            'local'
        );

        $pengumpulan->update([
            'file_path'    => $path,
            'file_name'    => $file->getClientOriginalName(),
            'file_size'    => $file->getSize(),
            'mime_type'    => $file->getMimeType(),
            'catatan'      => $request->catatan,
            'submitted_at' => now(),
        ]);

        return back()->with('success', 'File pengumpulan berhasil diperbarui.');
    }

    public function download(PengumpulanTugas $pengumpulan)
    {
        // Hanya dosen pengampu atau mahasiswa pemilik yang bisa download
        $user = Auth::user();

        if ($user->isMahasiswa() && $pengumpulan->mahasiswa_id !== $user->id) {
            abort(403);
        }

        if (!Storage::disk('local')->exists($pengumpulan->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk('local')->download(
            $pengumpulan->file_path,
            $pengumpulan->file_name
        );
    }

    private function authorizeTugas(Tugas $tugas): void
    {
        $kelasIds = Auth::user()->kelas()->pluck('kelas.id')->toArray();
        if (!in_array($tugas->kelas_id, $kelasIds)) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini.');
        }
    }
}