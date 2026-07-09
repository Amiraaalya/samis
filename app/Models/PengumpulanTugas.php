<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PengumpulanTugas extends Model
{
    use HasFactory;

    protected $table = 'pengumpulan_tugas';

    protected $fillable = [
        'tugas_id', 'mahasiswa_id', 'file_path',
        'file_name', 'file_size', 'mime_type',
        'catatan', 'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'file_size'    => 'integer',
        ];
    }

    public function tugas(): BelongsTo
    {
        return $this->belongsTo(Tugas::class, 'tugas_id');
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function penilaian(): HasOne
    {
        return $this->hasOne(Penilaian::class, 'pengumpulan_id');
    }

    public function sudahDinilai(): bool
    {
        return $this->penilaian()->exists();
    }

    public function fileSizeFormatted(): string
    {
        $kb = $this->file_size / 1024;
        if ($kb < 1024) {
            return round($kb, 1) . ' KB';
        }
        return round($kb / 1024, 2) . ' MB';
    }
}