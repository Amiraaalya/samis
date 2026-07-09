<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas', 'kode_kelas',
        'mata_kuliah', 'semester', 'dosen_id',
    ];

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    public function mahasiswa(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class, 'mahasiswa_kelas', 'kelas_id', 'mahasiswa_id'
        )->withTimestamps();
    }

    public function tugas(): HasMany
    {
        return $this->hasMany(Tugas::class, 'kelas_id');
    }

    public function tugasAktif(): HasMany
    {
        return $this->hasMany(Tugas::class, 'kelas_id')
                    ->whereNotIn('status', ['selesai', 'terlambat']);
    }
}