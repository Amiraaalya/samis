<?php

namespace App\Console\Commands;

use App\Models\Notifikasi;
use App\Models\Tugas;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SendDeadlineReminder extends Command
{
    protected $signature   = 'samis:send-reminder';
    protected $description = 'Kirim notifikasi reminder deadline tugas ke mahasiswa';

    public function handle(): void
    {
        $today = Carbon::now()->startOfDay();
        $sent  = 0;

        $tugas = Tugas::where('jenis_tugas', 'kelas')
            ->whereNotIn('status', ['selesai', 'terlambat'])
            ->where('deadline', '>=', $today)
            ->with('kelas.mahasiswa')
            ->get();

        foreach ($tugas as $t) {
            $deadlineDay = Carbon::parse($t->deadline)->startOfDay();
            $selisih     = (int) $today->diffInDays($deadlineDay);

            if ($selisih > 14) {
                continue;
            }

            $jenis = match(true) {
                $selisih === 0  => 'hari_h',
                $selisih === 1  => 'h1',
                $selisih <= 3   => 'h3',
                $selisih <= 7   => 'h7',
                default         => 'h7', // 8-14 hari juga dapat notif h7
            };

            $pesan = match($jenis) {
                'hari_h' => "Tugas \"{$t->judul}\" deadline hari ini! Segera kumpulkan.",
                'h1'     => "Tugas \"{$t->judul}\" deadline besok ({$deadlineDay->format('d M Y')}). Jangan lupa!",
                'h3'     => "Tugas \"{$t->judul}\" deadline dalam {$selisih} hari ({$deadlineDay->format('d M Y')}).",
                'h7'     => "Tugas \"{$t->judul}\" deadline dalam {$selisih} hari ({$deadlineDay->format('d M Y')}).",
                default  => "Reminder tugas \"{$t->judul}\".",
            };

            if (!$t->kelas || $t->kelas->mahasiswa->isEmpty()) {
                $this->warn("Tugas ID {$t->id} tidak punya kelas/mahasiswa, dilewati.");
                continue;
            }

            foreach ($t->kelas->mahasiswa as $mahasiswa) {
                $notif = Notifikasi::firstOrCreate(
                    [
                        'user_id'       => $mahasiswa->id,
                        'tugas_id'      => $t->id,
                        'jenis'         => $jenis,
                        'tanggal_kirim' => $today->toDateString(),
                    ],
                    [
                        'pesan'   => $pesan,
                        'is_read' => false,
                    ]
                );

                if ($notif->wasRecentlyCreated) {
                    $sent++;
                }
            }
        }

        $this->info("Selesai: {$sent} notifikasi baru dikirim — " . now()->toDateTimeString());
    }
}