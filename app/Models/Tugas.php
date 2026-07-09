<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Tugas extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelas_id', 'user_id', 'judul', 'deskripsi',
        'jenis_tugas', 'prioritas', 'status', 'progres', 'deadline',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'datetime',
        ];
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pengumpulan(): HasMany
    {
        return $this->hasMany(PengumpulanTugas::class, 'tugas_id');
    }

    public function notifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class, 'tugas_id');
    }

    public function scopeKelas(Builder $query): Builder
    {
        return $query->where('jenis_tugas', 'kelas');
    }

    public function scopePribadi(Builder $query): Builder
    {
        return $query->where('jenis_tugas', 'pribadi');
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->whereNotIn('status', ['selesai', 'terlambat']);
    }

    public function scopeTerlambat(Builder $query): Builder
    {
        return $query->where('deadline', '<', now())
                     ->where('status', '!=', 'selesai');
    }

    public function scopeMendekatiDeadline(Builder $query, int $hari = 7): Builder
    {
        return $query->whereBetween('deadline', [now(), now()->addDays($hari)]);
    }

    public function sisaHari(): int
    {
        return (int) now()->startOfDay()->diffInDays(
            $this->deadline->copy()->startOfDay(), false
        );
    }

    public function sudahTerlambat(): bool
    {
        return $this->deadline->isPast() && $this->status !== 'selesai';
    }

    public function labelStatus(): string
    {
        return match($this->status) {
            'belum_dikerjakan'  => 'Belum Dikerjakan',
            'sedang_dikerjakan' => 'Sedang Dikerjakan',
            'selesai'           => 'Selesai',
            'terlambat'         => 'Terlambat',
            default             => ucfirst($this->status),
        };
    }

    public function warnaPrioritas(): string
    {
        return match($this->prioritas) {
            'tinggi' => 'red',
            'sedang' => 'yellow',
            'rendah' => 'green',
            default  => 'gray',
        };
    }
}