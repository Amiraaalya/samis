<?php

namespace App\Console\Commands;

use App\Models\Notifikasi;
use App\Models\Tugas;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\Services\NotifikasiAdminService;

class UpdateStatusTerlambat extends Command
{
    protected $signature   = 'samis:update-terlambat';
    protected $description = 'Update status tugas yang melewati deadline menjadi terlambat';

    public function handle(): void
    {
        $today   = Carbon::today();
        $updated = 0;

        $tugasTerlambat = Tugas::where('jenis_tugas', 'kelas')
            ->where('deadline', '<', $today)
            ->whereNotIn('status', ['selesai', 'terlambat'])
            ->with('kelas.mahasiswa')
            ->get();

        foreach ($tugasTerlambat as $t) {
            $t->update(['status' => 'terlambat']);
            $belumKumpul = $t->kelas->mahasiswa->count() - $t->pengumpulan()->count();
            if ($belumKumpul > 0) {
                NotifikasiAdminService::mahasiswaTerlambat(
                    $belumKumpul,
                    $t->judul,
                    $t->kelas->nama_kelas
                );
            }
            $updated++;

            $jumlahMahasiswa = $t->kelas->mahasiswa->count();
            $pesanDosen      = "Tugas \"{$t->judul}\" telah melewati deadline. {$jumlahMahasiswa} mahasiswa di kelas {$t->kelas->nama_kelas} mungkin terlambat mengumpulkan.";

            // Notifikasi ke dosen
            Notifikasi::firstOrCreate(
                [
                    'user_id'       => $t->kelas->dosen_id,
                    'tugas_id'      => $t->id,
                    'jenis'         => 'terlambat',
                    'tanggal_kirim' => $today->toDateString(),
                ],
                [
                    'pesan'   => $pesanDosen,
                    'is_read' => false,
                ]
            );

            // Notifikasi ke mahasiswa yang belum mengumpulkan
            $sudahKumpulIds = $t->pengumpulan()->pluck('mahasiswa_id')->toArray();

            foreach ($t->kelas->mahasiswa as $mahasiswa) {
                if (in_array($mahasiswa->id, $sudahKumpulIds)) {
                    continue; // Skip yang sudah kumpul
                }

                Notifikasi::firstOrCreate(
                    [
                        'user_id'       => $mahasiswa->id,
                        'tugas_id'      => $t->id,
                        'jenis'         => 'terlambat',
                        'tanggal_kirim' => $today->toDateString(),
                    ],
                    [
                        'pesan'   => "Tugas \"{$t->judul}\" telah melewati deadline dan ditandai terlambat.",
                        'is_read' => false,
                    ]
                );
            }
        }

        $this->info("Selesai: {$updated} tugas diupdate menjadi terlambat — " . now()->toDateTimeString());

        if ($updated > 0) {
            NotifikasiAdminService::warning(
                'Tugas Melewati Deadline',
                "{$updated} tugas telah melewati deadline dan diupdate statusnya menjadi terlambat.",
                'fa-clock',
                route('admin.monitoring')
            );
        }

        NotifikasiAdminService::success(
            'Scheduler Reminder Berjalan',
            "Scheduler reminder deadline berhasil dijalankan pada " . now()->format('d M Y H:i') . ".",
            'fa-check-circle'
        );
    }
}