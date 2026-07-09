<?php

namespace App\Http\Middleware;

use App\Models\Notifikasi;
use App\Models\Tugas;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class GenerateHarianNotifikasi
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user  = Auth::user();
            $today = Carbon::today()->toDateString();

            // Pakai cache per user per hari agar tidak query berulang di setiap request
            $cacheKey = "notif_harian_{$user->id}_{$today}";

            if (!Cache::has($cacheKey)) {
                if ($user->isMahasiswa()) {
                    $this->generateUntukMahasiswa($user, $today);
                } elseif ($user->isDosen()) {
                    $this->generateUntukDosen($user, $today);
                }

                // Cache selama 6 jam agar tidak diproses terus tiap request
                Cache::put($cacheKey, true, now()->addHours(6));
            }
        }

        return $next($request);
    }

    private function generateUntukMahasiswa($user, string $today): void
    {
        $todayCarbon = Carbon::today();
        $kelasIds    = $user->kelas()->pluck('kelas.id');

        $tugas = Tugas::where('jenis_tugas', 'kelas')
            ->whereIn('kelas_id', $kelasIds)
            ->whereNotIn('status', ['selesai'])
            ->where('deadline', '>=', $todayCarbon)
            ->get();

        foreach ($tugas as $t) {
            $deadlineDay = Carbon::parse($t->deadline)->startOfDay();
            $selisih     = (int) $todayCarbon->diffInDays($deadlineDay);

            if ($selisih > 14) {
                continue;
            }

            $jenis = match(true) {
                $selisih === 0 => 'hari_h',
                $selisih === 1 => 'h1',
                $selisih <= 3  => 'h3',
                default        => 'h7',
            };

            $pesan = match($jenis) {
                'hari_h' => "Deadline hari ini! Segera kumpulkan tugas \"{$t->judul}\".",
                'h1'     => "Deadline besok ({$deadlineDay->format('d M Y')}) untuk tugas \"{$t->judul}\".",
                'h3'     => "Tugas \"{$t->judul}\" deadline dalam {$selisih} hari ({$deadlineDay->format('d M Y')}).",
                default  => "Tugas \"{$t->judul}\" deadline dalam {$selisih} hari ({$deadlineDay->format('d M Y')}).",
            };

            Notifikasi::firstOrCreate(
                [
                    'user_id'       => $user->id,
                    'tugas_id'      => $t->id,
                    'jenis'         => $jenis,
                    'tanggal_kirim' => $today,
                ],
                [
                    'pesan'   => $pesan,
                    'is_read' => false,
                ]
            );
        }

        // Tugas yang sudah terlambat (deadline lewat, belum kumpul)
        $tugasTerlambat = Tugas::where('jenis_tugas', 'kelas')
            ->whereIn('kelas_id', $kelasIds)
            ->where('status', 'terlambat')
            ->whereDoesntHave('pengumpulan', fn($q) => $q->where('mahasiswa_id', $user->id))
            ->get();

        foreach ($tugasTerlambat as $t) {
            Notifikasi::firstOrCreate(
                [
                    'user_id'       => $user->id,
                    'tugas_id'      => $t->id,
                    'jenis'         => 'terlambat',
                    'tanggal_kirim' => $today,
                ],
                [
                    'pesan'   => "Tugas \"{$t->judul}\" sudah melewati deadline dan Anda belum mengumpulkan.",
                    'is_read' => false,
                ]
            );
        }
    }

    private function generateUntukDosen($user, string $today): void
    {
        $todayCarbon = Carbon::today();
        $kelasIds    = $user->kelasYangDiampu()->pluck('id');

        // Reminder tugas yang mendekati deadline
        $tugas = Tugas::where('jenis_tugas', 'kelas')
            ->whereIn('kelas_id', $kelasIds)
            ->whereNotIn('status', ['selesai', 'terlambat'])
            ->where('deadline', '>=', $todayCarbon)
            ->with('kelas')
            ->get();

        foreach ($tugas as $t) {
            $deadlineDay = Carbon::parse($t->deadline)->startOfDay();
            $selisih     = (int) $todayCarbon->diffInDays($deadlineDay);

            if ($selisih > 3) {
                continue; // Dosen hanya dapat reminder H-3 dan H-1/H-0
            }

            $jenis = match(true) {
                $selisih === 0 => 'hari_h',
                $selisih === 1 => 'h1',
                default        => 'h3',
            };

            $pesan = match($jenis) {
                'hari_h' => "Deadline hari ini untuk tugas \"{$t->judul}\" di kelas {$t->kelas->nama_kelas}.",
                'h1'     => "Tugas \"{$t->judul}\" di kelas {$t->kelas->nama_kelas} deadline besok ({$deadlineDay->format('d M Y')}).",
                default  => "Tugas \"{$t->judul}\" di kelas {$t->kelas->nama_kelas} deadline dalam {$selisih} hari ({$deadlineDay->format('d M Y')}).",
            };

            Notifikasi::firstOrCreate(
                [
                    'user_id'       => $user->id,
                    'tugas_id'      => $t->id,
                    'jenis'         => $jenis,
                    'tanggal_kirim' => $today,
                ],
                [
                    'pesan'   => $pesan,
                    'is_read' => false,
                ]
            );
        }

        // Tugas yang baru saja melewati deadline hari ini
        $tugasTerlambat = Tugas::where('jenis_tugas', 'kelas')
            ->whereIn('kelas_id', $kelasIds)
            ->where('status', 'terlambat')
            ->whereDate('deadline', $today)
            ->with('kelas')
            ->get();

        foreach ($tugasTerlambat as $t) {
            Notifikasi::firstOrCreate(
                [
                    'user_id'       => $user->id,
                    'tugas_id'      => $t->id,
                    'jenis'         => 'terlambat',
                    'tanggal_kirim' => $today,
                ],
                [
                    'pesan'   => "Tugas \"{$t->judul}\" di kelas {$t->kelas->nama_kelas} telah melewati deadline hari ini.",
                    'is_read' => false,
                ]
            );
        }
    }
}