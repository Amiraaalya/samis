<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotifikasiAdmin extends Model
{
    use HasFactory;

    protected $table = 'notifikasi_admin';

    protected $fillable = [
        'user_id', 'judul', 'pesan',
        'tipe', 'icon', 'url_tujuan', 'is_read',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ── Helper warna berdasarkan tipe ─────────────────────────────────────
    public function warnaBg(): string
    {
        return match($this->tipe) {
            'success' => 'bg-green-50 border-green-200',
            'warning' => 'bg-amber-50 border-amber-200',
            'danger'  => 'bg-red-50 border-red-200',
            default   => 'bg-blue-50 border-blue-200',
        };
    }

    public function warnaIcon(): string
    {
        return match($this->tipe) {
            'success' => 'bg-green-100 text-green-600',
            'warning' => 'bg-amber-100 text-amber-600',
            'danger'  => 'bg-red-100 text-red-600',
            default   => 'bg-blue-100 text-blue-600',
        };
    }

    public function warnaBadge(): string
    {
        return match($this->tipe) {
            'success' => 'bg-green-100 text-green-700',
            'warning' => 'bg-amber-100 text-amber-700',
            'danger'  => 'bg-red-100 text-red-700',
            default   => 'bg-blue-100 text-blue-700',
        };
    }

    public function labelTipe(): string
    {
        return match($this->tipe) {
            'success' => 'Berhasil',
            'warning' => 'Peringatan',
            'danger'  => 'Bahaya',
            default   => 'Info',
        };
    }
}