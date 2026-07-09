<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
    'name', 'email', 'password',
    'role', 'nim_nip', 'is_active', 'avatar',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password'  => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function isAdmin(): bool    { return $this->role === 'admin'; }
    public function isDosen(): bool    { return $this->role === 'dosen'; }
    public function isMahasiswa(): bool { return $this->role === 'mahasiswa'; }

    public function kelasYangDiampu(): HasMany
    {
        return $this->hasMany(Kelas::class, 'dosen_id');
    }

    public function kelas(): BelongsToMany
    {
        return $this->belongsToMany(
            Kelas::class, 'mahasiswa_kelas', 'mahasiswa_id', 'kelas_id'
        )->withTimestamps();
    }

    public function tugas(): HasMany
    {
        return $this->hasMany(Tugas::class, 'user_id');
    }

    public function pengumpulan(): HasMany
    {
        return $this->hasMany(PengumpulanTugas::class, 'mahasiswa_id');
    }

    public function penilaianDiberikan(): HasMany
    {
        return $this->hasMany(Penilaian::class, 'dosen_id');
    }

    public function notifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class, 'user_id');
    }

    public function notifikasiUnread(): HasMany
    {
        return $this->hasMany(Notifikasi::class, 'user_id')
                    ->where('is_read', false);
    }

    public function aktivitasLog(): HasMany
    {
        return $this->hasMany(AktivitasLog::class, 'user_id');
    }

   public function avatarUrl(): string
    {
        if ($this->avatar && file_exists(public_path('storage/' . $this->avatar))) {
            return asset('storage/' . $this->avatar);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name)
            . '&background=6366f1&color=fff&bold=true&size=128';
    }
}