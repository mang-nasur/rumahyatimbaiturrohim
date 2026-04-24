<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'anak_yatim_id',
        'status_akun',
        'catatan_penolakan',
        'approved_by',
        'approved_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'approved_at'       => 'datetime',
    ];

    // ─── Status Akun ──────────────────────────────────────────────────────────

    public function isPending(): bool
    {
        return $this->status_akun === 'pending';
    }

    public function isAktif(): bool
    {
        return $this->status_akun === 'aktif';
    }

    public function isDitolak(): bool
    {
        return $this->status_akun === 'ditolak';
    }

    public function approvedByUser()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ─── Role Helpers ─────────────────────────────────────────────────────────

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isBendahara(): bool
    {
        return $this->hasRole('bendahara');
    }

    public function isStaff(): bool
    {
        return $this->hasRole('staff');
    }

    public function isOrangTua(): bool
    {
        return $this->hasRole('orang_tua');
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    /**
     * Relasi ke anak yatim yang diwakili (khusus role orang_tua).
     */
    public function anakYatim()
    {
        return $this->belongsTo(\App\Models\AnakYatim::class, 'anak_yatim_id');
    }

    // ─── Permissions ─────────────────────────────────────────────────────────

    public function canAccess(string $feature): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        $permissions = [
            'bendahara' => [
                'transaksi.create', 'transaksi.read', 'transaksi.update', 'transaksi.delete',
                'anak-yatim.read',
                'laporan.read', 'laporan.export',
                'dashboard.read',
            ],
            'staff' => [
                'anak-yatim.create', 'anak-yatim.read', 'anak-yatim.update', 'anak-yatim.delete',
                'laporan.read', 'laporan.export',
                'dashboard.read',
            ],
            'orang_tua' => [
                'anak-yatim.read', 'anak-yatim.update',
                'absensi.create',
                'dashboard.read',
            ],
        ];

        return in_array($feature, $permissions[$this->role] ?? []);
    }
}
