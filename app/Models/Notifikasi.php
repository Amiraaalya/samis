<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';

    protected $fillable = [
        'user_id', 'tugas_id', 'jenis',
        'pesan', 'is_read', 'tanggal_kirim',
    ];

    protected function casts(): array
    {
        return [
            'is_read'       => 'boolean',
            'tanggal_kirim' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tugas(): BelongsTo
    {
        return $this->belongsTo(Tugas::class, 'tugas_id');
    }

    public function tandaiDibaca(): void
    {
        $this->update(['is_read' => true]);
    }

    public function getLabelJenis(): string
    {
        // Untuk h7, h3, h1 — ekstrak angka aktual dari pesan
        if (in_array($this->jenis, ['h7', 'h3', 'h1'])) {
            preg_match('/dalam (\d+) hari/', $this->pesan, $matches);
            if (!empty($matches[1])) {
                return $matches[1] . ' hari lagi';
            }
        }

        return match($this->jenis) {
            'h7'        => '7 hari lagi',
            'h3'        => '3 hari lagi',
            'h1'        => 'Besok deadline',
            'hari_h'    => 'Deadline hari ini',
            'terlambat' => 'Tugas terlambat',
            'selesai'   => 'Tugas dinilai',
            default     => $this->jenis,
        };
    }
}