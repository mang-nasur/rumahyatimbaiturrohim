<?php

namespace App\Services;

use App\Models\Transaksi;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class TransaksiService
{
    /**
     * Create a new transaction
     *
     * @param array $data
     * @return Transaksi
     * @throws \Exception
     */
    public function createTransaksi(array $data): Transaksi
    {
        return DB::transaction(function () use ($data) {
            // Handle file upload if provided
            if (isset($data['bukti_file']) && $data['bukti_file'] instanceof UploadedFile) {
                $data['bukti_file'] = $this->handleFileUpload($data['bukti_file']);
            }

            // Create the transaction
            return Transaksi::create($data);
        });
    }

    /**
     * Update an existing transaction
     *
     * @param Transaksi $transaksi
     * @param array $data
     * @return Transaksi
     * @throws \Exception
     */
    public function updateTransaksi(Transaksi $transaksi, array $data): Transaksi
    {
        return DB::transaction(function () use ($transaksi, $data) {
            $oldFile = $transaksi->bukti_file;

            // Handle file upload if new file provided
            if (isset($data['bukti_file']) && $data['bukti_file'] instanceof UploadedFile) {
                $data['bukti_file'] = $this->handleFileUpload($data['bukti_file']);
                
                // Delete old file if it exists
                if ($oldFile) {
                    $this->deleteFile($oldFile);
                }
            } else {
                // Keep existing file if no new file uploaded
                unset($data['bukti_file']);
            }

            // Update the transaction
            $transaksi->update($data);
            
            return $transaksi->fresh();
        });
    }

    /**
     * Delete a transaction
     *
     * @param Transaksi $transaksi
     * @return bool
     * @throws \Exception
     */
    public function deleteTransaksi(Transaksi $transaksi): bool
    {
        return DB::transaction(function () use ($transaksi) {
            // Delete associated file if exists
            if ($transaksi->bukti_file) {
                $this->deleteFile($transaksi->bukti_file);
            }

            // Delete the transaction
            return $transaksi->delete();
        });
    }

    /**
     * Calculate current cash balance
     *
     * @return float
     */
    public function getSaldo(): float
    {
        $totalPenerimaan = Transaksi::penerimaan()->sum('jumlah') ?? 0;
        $totalPengeluaran = Transaksi::pengeluaran()->sum('jumlah') ?? 0;

        return (float) ($totalPenerimaan - $totalPengeluaran);
    }

    /**
     * Get transactions with filters applied
     *
     * @param array $filters
     * @return Builder
     */
    public function getTransaksiWithFilters(array $filters): Builder
    {
        $query = Transaksi::query();

        // Filter by date range
        if (!empty($filters['tanggal_dari']) && !empty($filters['tanggal_sampai'])) {
            $query->byDateRange($filters['tanggal_dari'], $filters['tanggal_sampai']);
        }

        // Filter by jenis (type)
        if (!empty($filters['jenis'])) {
            $query->where('jenis', $filters['jenis']);
        }

        // Filter by kategori (category)
        if (!empty($filters['kategori'])) {
            $query->byKategori($filters['kategori']);
        }

        // Search by keterangan (description)
        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        // Order by date descending (most recent first)
        $query->orderBy('tanggal', 'desc')->orderBy('id', 'desc');

        return $query;
    }

    /**
     * Handle file upload and return the stored path
     *
     * @param UploadedFile $file
     * @return string
     */
    private function handleFileUpload(UploadedFile $file): string
    {
        // Generate unique filename
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        
        // Store file in 'bukti' directory
        $path = $file->storeAs('bukti', $filename, 'public');
        
        return $path;
    }

    /**
     * Delete file from storage
     *
     * @param string $path
     * @return bool
     */
    private function deleteFile(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        
        return false;
    }
}
