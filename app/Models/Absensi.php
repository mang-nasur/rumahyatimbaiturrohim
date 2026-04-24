<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';

    protected $fillable = [
        'anak_yatim_id',
        'bulan',
        'tahun',
        'hadir_sebagai',
        'status',
        'catatan_staff',
        'approved_by',
        'approved_at',
        'submitted_at',
    ];

    protected $casts = [
        'approved_at'  => 'datetime',
        'submitted_at' => 'datetime',
        'bulan'        => 'integer',
        'tahun'        => 'integer',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function anakYatim()
    {
        return $this->belongsTo(AnakYatim::class, 'anak_yatim_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeDisetujui($query)
    {
        return $query->where('status', 'disetujui');
    }

    public function scopePeriode($query, int $bulan, int $tahun)
    {
        return $query->where('bulan', $bulan)->where('tahun', $tahun);
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    /**
     * Nama bulan dalam Bahasa Indonesia.
     */
    public function getNamaBulanAttribute(): string
    {
        $bulanMap = [
            1  => 'Januari',  2  => 'Februari', 3  => 'Maret',
            4  => 'April',    5  => 'Mei',       6  => 'Juni',
            7  => 'Juli',     8  => 'Agustus',   9  => 'September',
            10 => 'Oktober',  11 => 'November',  12 => 'Desember',
        ];

        return $bulanMap[$this->bulan] ?? '-';
    }

    /**
     * Label status dalam Bahasa Indonesia.
     */
    public function getLabelStatusAttribute(): string
    {
        return match ($this->status) {
            'pending'   => 'Menunggu Persetujuan',
            'disetujui' => 'Disetujui',
            'ditolak'   => 'Ditolak',
            default     => '-',
        };
    }

    /**
     * Badge Bootstrap class berdasarkan status.
     */
    public function getBadgeStatusAttribute(): string
    {
        return match ($this->status) {
            'pending'   => 'warning',
            'disetujui' => 'success',
            'ditolak'   => 'danger',
            default     => 'secondary',
        };
    }
}
