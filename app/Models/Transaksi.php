<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transaksi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tanggal',
        'jenis',
        'kategori',
        'jumlah',
        'keterangan',
        'bukti_file',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
    ];

    /**
     * Jenis transaksi constants.
     */
    const JENIS_PENERIMAAN = 'penerimaan';
    const JENIS_PENGELUARAN = 'pengeluaran';

    /**
     * Kategori penerimaan options.
     *
     * @var array<int, string>
     */
    const KATEGORI_PENERIMAAN = [
        'Donasi Individu',
        'Donasi Perusahaan',
        'Bantuan Pemerintah',
        'Lainnya',
    ];

    /**
     * Kategori pengeluaran options.
     *
     * @var array<int, string>
     */
    const KATEGORI_PENGELUARAN = [
        'Kebutuhan Anak',
        'Operasional Panti',
        'Pendidikan',
        'Kesehatan',
        'Lainnya',
    ];

    /**
     * Get formatted amount in rupiah format.
     *
     * @return string
     */
    public function getFormattedJumlahAttribute(): string
    {
        return 'Rp ' . number_format($this->jumlah, 0, ',', '.');
    }

    /**
     * Check if transaction is a receipt (penerimaan).
     *
     * @return bool
     */
    public function isPenerimaan(): bool
    {
        return $this->jenis === self::JENIS_PENERIMAAN;
    }

    /**
     * Check if transaction is an expense (pengeluaran).
     *
     * @return bool
     */
    public function isPengeluaran(): bool
    {
        return $this->jenis === self::JENIS_PENGELUARAN;
    }

    /**
     * Scope a query to only include receipt transactions.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePenerimaan($query)
    {
        return $query->where('jenis', self::JENIS_PENERIMAAN);
    }

    /**
     * Scope a query to only include expense transactions.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePengeluaran($query)
    {
        return $query->where('jenis', self::JENIS_PENGELUARAN);
    }

    /**
     * Scope a query to filter by date range.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $startDate
     * @param string $endDate
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal', [$startDate, $endDate]);
    }

    /**
     * Scope a query to filter by category.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $kategori
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByKategori($query, string $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    /**
     * Scope a query to search by description.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $term
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, string $term)
    {
        return $query->where('keterangan', 'like', "%{$term}%");
    }
}
