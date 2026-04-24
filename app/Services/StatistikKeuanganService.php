<?php

namespace App\Services;

use App\Models\Transaksi;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class StatistikKeuanganService
{
    /**
     * Get current cash balance
     * 
     * @return float
     */
    public function getSaldoKas(): float
    {
        $totalPenerimaan = Transaksi::penerimaan()->sum('jumlah') ?? 0;
        $totalPengeluaran = Transaksi::pengeluaran()->sum('jumlah') ?? 0;

        return (float) ($totalPenerimaan - $totalPengeluaran);
    }

    /**
     * Get total receipts for current month
     * 
     * @return float
     */
    public function getTotalPenerimaanBulanIni(): float
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        return (float) Transaksi::penerimaan()
            ->byDateRange($startOfMonth, $endOfMonth)
            ->sum('jumlah') ?? 0;
    }

    /**
     * Get total expenses for current month
     * 
     * @return float
     */
    public function getTotalPengeluaranBulanIni(): float
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        return (float) Transaksi::pengeluaran()
            ->byDateRange($startOfMonth, $endOfMonth)
            ->sum('jumlah') ?? 0;
    }

    /**
     * Get chart data for receipts vs expenses
     * 
     * @param int $months Number of months to include (default: 6)
     * @return array
     */
    public function getGrafikPenerimaanVsPengeluaran(int $months = 6): array
    {
        $labels = [];
        $penerimaan = [];
        $pengeluaran = [];

        // Generate data for last N months
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            // Format label as "Jan 2024"
            $labels[] = $date->format('M Y');

            // Get total penerimaan for this month
            $penerimaan[] = (float) Transaksi::penerimaan()
                ->byDateRange($startOfMonth, $endOfMonth)
                ->sum('jumlah') ?? 0;

            // Get total pengeluaran for this month
            $pengeluaran[] = (float) Transaksi::pengeluaran()
                ->byDateRange($startOfMonth, $endOfMonth)
                ->sum('jumlah') ?? 0;
        }

        return [
            'labels' => $labels,
            'penerimaan' => $penerimaan,
            'pengeluaran' => $pengeluaran,
        ];
    }

    /**
     * Get recent transactions
     * 
     * @param int $limit Number of transactions to retrieve (default: 10)
     * @return Collection
     */
    public function getTransaksiTerbaru(int $limit = 10): Collection
    {
        return Transaksi::orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->get();
    }
}
