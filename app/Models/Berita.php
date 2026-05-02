<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Berita extends Model
{
    use HasFactory;

    protected $table = 'berita';

    protected $fillable = [
        'judul',
        'slug',
        'ringkasan',
        'isi',
        'foto',
        'kategori',
        'status',
        'tanggal_publikasi',
        'user_id',
    ];

    protected $casts = [
        'tanggal_publikasi' => 'date',
    ];

    // ─── Kategori ─────────────────────────────────────────────────────────────

    const KATEGORI = [
        'Umum',
        'Kegiatan',
        'Prestasi',
        'Donasi',
        'Pengumuman',
        'Ramadan',
    ];

    // ─── Accessors ────────────────────────────────────────────────────────────

    public function getFotoUrlAttribute(): ?string
    {
        if (!$this->foto) {
            return null;
        }
        return Storage::url($this->foto);
    }

    public function getRingkasanAutoAttribute(): string
    {
        if ($this->ringkasan) {
            return $this->ringkasan;
        }
        // Buat ringkasan otomatis dari isi (strip HTML, ambil 150 karakter)
        return Str::limit(strip_tags($this->isi), 150);
    }

    public function getTanggalFormatAttribute(): string
    {
        if (!$this->tanggal_publikasi) {
            return '-';
        }
        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        return $this->tanggal_publikasi->day . ' ' .
               $bulan[$this->tanggal_publikasi->month] . ' ' .
               $this->tanggal_publikasi->year;
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->whereNotNull('tanggal_publikasi')
                     ->where('tanggal_publikasi', '<=', now()->toDateString());
    }

    public function scopeLatest3($query)
    {
        return $query->published()->orderByDesc('tanggal_publikasi')->limit(3);
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function penulis()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Generate slug unik dari judul.
     */
    public static function generateSlug(string $judul, ?int $exceptId = null): string
    {
        $base = Str::slug($judul);
        $slug = $base;
        $i    = 1;

        while (
            static::where('slug', $slug)
                  ->when($exceptId, fn($q) => $q->where('id', '!=', $exceptId))
                  ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
