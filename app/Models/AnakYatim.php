<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AnakYatim extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'anak_yatim';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'nama_ayah',
        'status_ayah',
        'nama_ibu',
        'status_ibu',
        'nomor_telepon_wali',
        'tanggal_masuk',
        'pendidikan_terakhir',
        'sekolah_saat_ini',
        'foto',
        'nik',
        'no_kk',
        'kelas_saat_masuk',
        'tanggal_keluar',
        'is_aktif',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_lahir'  => 'date',
        'tanggal_masuk'  => 'date',
        'tanggal_keluar' => 'date',
        'is_aktif'       => 'boolean',
    ];

    /**
     * Get the child's age calculated from birth date.
     *
     * @return int
     */
    public function getUsiaAttribute(): int
    {
        return $this->tanggal_lahir->age;
    }

    /**
     * Get the estimated exit date based on entry date and class at entry.
     */
    public function getEstimasiKeluarAttribute(): ?\Carbon\Carbon
    {
        return \App\Services\EstimasiKeluarCalculator::calculate(
            $this->tanggal_masuk,
            $this->kelas_saat_masuk,
            $this->tanggal_lahir
        );
    }

    /**
     * Hitung kelas anak sekarang secara fleksibel.
     *
     * Algoritma:
     * 1. Tentukan nomor tingkat saat masuk (1–12).
     *    - Jika "Belum Sekolah": hitung dari tanggal_lahir, asumsi masuk kelas 1 SD usia 7 tahun.
     * 2. Hitung selisih tahun ajaran dari tanggal_masuk ke sekarang.
     *    - Tahun ajaran berganti setiap Juli.
     * 3. nomorSekarang = nomorMasuk + selisihTahunAjaran
     * 4. Kembalikan nama kelas, atau "Lulus SMA" jika > 12.
     */
    public function getKelasSekarangAttribute(): string
    {
        $kelasMap = \App\Services\EstimasiKeluarCalculator::KELAS_MAP;
        $kelasMapFlip = array_flip($kelasMap); // [1 => 'Kelas 1 SD', ...]

        // Tentukan nomor tingkat saat masuk
        if ($this->kelas_saat_masuk === 'Belum Sekolah' || !$this->kelas_saat_masuk) {
            // Hitung dari tanggal lahir: masuk kelas 1 SD usia 7 tahun
            if (!$this->tanggal_lahir) return 'Belum Sekolah';

            $tahunLahir  = (int) $this->tanggal_lahir->format('Y');
            $bulanLahir  = (int) $this->tanggal_lahir->format('n');
            $tahunMasukSD = $tahunLahir + 7 + ($bulanLahir > 6 ? 1 : 0);

            // Tahun ajaran saat masuk SD
            $tahunAjaranMasuk = $tahunMasukSD;
            $nomorMasuk = 1; // Kelas 1 SD
        } else {
            $nomorMasuk = $kelasMap[$this->kelas_saat_masuk] ?? null;
            if ($nomorMasuk === null) return $this->kelas_saat_masuk;

            // Tahun ajaran saat masuk yayasan
            $bulanMasuk = (int) $this->tanggal_masuk->format('n');
            $tahunMasuk = (int) $this->tanggal_masuk->format('Y');
            // Tahun ajaran dimulai Juli; jika masuk Jan–Jun, tahun ajaran = tahun itu
            $tahunAjaranMasuk = $bulanMasuk >= 7 ? $tahunMasuk + 1 : $tahunMasuk;
        }

        // Tahun ajaran sekarang
        $bulanSekarang = (int) now()->format('n');
        $tahunSekarang = (int) now()->format('Y');
        $tahunAjaranSekarang = $bulanSekarang >= 7 ? $tahunSekarang + 1 : $tahunSekarang;

        $selisih = $tahunAjaranSekarang - $tahunAjaranMasuk;
        $nomorSekarang = $nomorMasuk + $selisih;

        if ($nomorSekarang < 1) return 'Belum Sekolah';
        if ($nomorSekarang > 12) return 'Lulus SMA';

        return $kelasMapFlip[$nomorSekarang] ?? '-';
    }

    /**
     * Get the full URL for the child's photo.
     *
     * @return string|null
     */
    public function getFotoUrlAttribute(): ?string
    {
        if (!$this->foto) {
            return null;
        }

        return Storage::url($this->foto);
    }

    /**
     * Scope a query to search by name (child, father, or mother).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $keyword
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, string $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('nama_lengkap', 'like', "%{$keyword}%")
              ->orWhere('nama_ayah', 'like', "%{$keyword}%")
              ->orWhere('nama_ibu', 'like', "%{$keyword}%");
        });
    }

    /**
     * Scope a query to filter by age range.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $minAge
     * @param int $maxAge
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByAgeRange($query, int $minAge, int $maxAge)
    {
        $maxDate = now()->subYears($minAge)->endOfDay();
        $minDate = now()->subYears($maxAge + 1)->startOfDay();

        return $query->whereBetween('tanggal_lahir', [$minDate, $maxDate]);
    }

    /**
     * Scope a query to filter by education level.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $pendidikan
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByPendidikan($query, string $pendidikan)
    {
        return $query->where('pendidikan_terakhir', $pendidikan);
    }

    /**
     * Scope a query to filter by entry year.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $tahun
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByTahunMasuk($query, int $tahun)
    {
        return $query->whereYear('tanggal_masuk', $tahun);
    }

    /**
     * Scope a query to filter by kelas_saat_masuk range (min to max).
     * Uses the KELAS_MAP from EstimasiKeluarCalculator to determine order.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $kelasMin
     * @param string|null $kelasMax
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByKelasRange($query, ?string $kelasMin, ?string $kelasMax)
    {
        $kelasMap = \App\Services\EstimasiKeluarCalculator::KELAS_MAP;

        $nomorMin = $kelasMin ? ($kelasMap[$kelasMin] ?? null) : null;
        $nomorMax = $kelasMax ? ($kelasMap[$kelasMax] ?? null) : null;

        if ($nomorMin === null && $nomorMax === null) {
            return $query;
        }

        // Kumpulkan semua kelas yang masuk dalam rentang
        $kelasDalamRange = collect($kelasMap)
            ->filter(function ($nomor) use ($nomorMin, $nomorMax) {
                if ($nomorMin !== null && $nomor < $nomorMin) return false;
                if ($nomorMax !== null && $nomor > $nomorMax) return false;
                return true;
            })
            ->keys()
            ->toArray();

        return $query->whereIn('kelas_saat_masuk', $kelasDalamRange);
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    /**
     * Semua record absensi anak ini.
     */
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'anak_yatim_id');
    }

    /**
     * User orang tua / wali yang terhubung ke anak ini.
     */
    public function orangTuaUser()
    {
        return $this->hasOne(\App\Models\User::class, 'anak_yatim_id');
    }

    /**
     * Hitung berapa bulan berturut-turut anak ini tidak hadir (status != disetujui).
     * Dihitung mundur dari bulan sekarang.
     */
    public function getTidakHadirBerturut(): int
    {
        $count = 0;
        $bulan = (int) now()->format('n');
        $tahun = (int) now()->format('Y');

        for ($i = 0; $i < 12; $i++) {
            $absensi = $this->absensi()
                ->where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->where('status', 'disetujui')
                ->first();

            if ($absensi) {
                break;
            }

            $count++;

            // Mundur satu bulan
            $bulan--;
            if ($bulan === 0) {
                $bulan = 12;
                $tahun--;
            }
        }

        return $count;
    }

    /**
     * Scope a query to only include active children (masih terdaftar).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }

    /**
     * Scope a query to only include non-active children (sudah keluar/lulus).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNonAktif($query)
    {
        return $query->where('is_aktif', false);
    }
}
