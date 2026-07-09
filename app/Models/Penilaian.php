<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penilaian extends Model
{
    use HasFactory;

    protected $table = 'penilaian';

    protected $fillable = [
        'pengumpulan_id', 'dosen_id',
        'nilai', 'feedback', 'dinilai_at',
    ];

    protected function casts(): array
    {
        return [
            'nilai'      => 'decimal:2',
            'dinilai_at' => 'datetime',
        ];
    }

    public function pengumpulan(): BelongsTo
    {
        return $this->belongsTo(PengumpulanTugas::class, 'pengumpulan_id');
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    public function getGradeLabel(): string
    {
        return match(true) {
            $this->nilai >= 85 => 'A',
            $this->nilai >= 75 => 'B',
            $this->nilai >= 65 => 'C',
            $this->nilai >= 55 => 'D',
            default            => 'E',
        };
    }
}