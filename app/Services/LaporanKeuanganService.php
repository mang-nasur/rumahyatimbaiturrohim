<?php

namespace App\Services;

use App\Models\Transaksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanKeuanganExport;

class LaporanKeuanganService
{
    /**
     * Generate receipt report (Laporan Kas Masuk)
     *
     * @param string $startDate
     * @param string $endDate
     * @param string|null $kategori
     * @return array
     */
    public function getLaporanKasMasuk(string $startDate, string $endDate, ?string $kategori = null): array
    {
        $query = Transaksi::penerimaan()
            ->byDateRange($startDate, $endDate);

        if ($kategori) {
            $query->byKategori($kategori);
        }

        $transaksi = $query->orderBy('tanggal')->get();

        // Group by category and calculate subtotals
        $groupedByKategori = $transaksi->groupBy('kategori')->map(function ($items) {
            return [
                'transaksi' => $items,
                'subtotal' => $items->sum('jumlah')
            ];
        });

        $grandTotal = $transaksi->sum('jumlah');

        return [
            'transaksi' => $transaksi,
            'grouped' => $groupedByKategori,
            'grand_total' => $grandTotal,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'kategori' => $kategori
        ];
    }

    /**
     * Generate expense report (Laporan Kas Keluar)
     *
     * @param string $startDate
     * @param string $endDate
     * @param string|null $kategori
     * @return array
     */
    public function getLaporanKasKeluar(string $startDate, string $endDate, ?string $kategori = null): array
    {
        $query = Transaksi::pengeluaran()
            ->byDateRange($startDate, $endDate);

        if ($kategori) {
            $query->byKategori($kategori);
        }

        $transaksi = $query->orderBy('tanggal')->get();

        // Group by category and calculate subtotals
        $groupedByKategori = $transaksi->groupBy('kategori')->map(function ($items) {
            return [
                'transaksi' => $items,
                'subtotal' => $items->sum('jumlah')
            ];
        });

        $grandTotal = $transaksi->sum('jumlah');

        return [
            'transaksi' => $transaksi,
            'grouped' => $groupedByKategori,
            'grand_total' => $grandTotal,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'kategori' => $kategori
        ];
    }

    /**
     * Generate cash flow report (Laporan Arus Kas)
     *
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getLaporanArusKas(string $startDate, string $endDate): array
    {
        // Calculate opening balance (all transactions before start date)
        $penerimaanSebelum = Transaksi::penerimaan()
            ->where('tanggal', '<', $startDate)
            ->sum('jumlah');
        
        $pengeluaranSebelum = Transaksi::pengeluaran()
            ->where('tanggal', '<', $startDate)
            ->sum('jumlah');
        
        $openingBalance = $penerimaanSebelum - $pengeluaranSebelum;

        // Get all transactions in period chronologically
        $transaksi = Transaksi::byDateRange($startDate, $endDate)
            ->orderBy('tanggal')
            ->orderBy('id')
            ->get();

        // Calculate running balance for each transaction
        $runningBalance = $openingBalance;
        $transaksiWithBalance = $transaksi->map(function ($t) use (&$runningBalance) {
            if ($t->isPenerimaan()) {
                $runningBalance += $t->jumlah;
            } else {
                $runningBalance -= $t->jumlah;
            }
            
            return [
                'transaksi' => $t,
                'running_balance' => $runningBalance
            ];
        });

        // Calculate totals in period
        $totalPenerimaan = $transaksi->where('jenis', Transaksi::JENIS_PENERIMAAN)->sum('jumlah');
        $totalPengeluaran = $transaksi->where('jenis', Transaksi::JENIS_PENGELUARAN)->sum('jumlah');
        $closingBalance = $openingBalance + $totalPenerimaan - $totalPengeluaran;

        return [
            'opening_balance' => $openingBalance,
            'transaksi' => $transaksiWithBalance,
            'total_penerimaan' => $totalPenerimaan,
            'total_pengeluaran' => $totalPengeluaran,
            'closing_balance' => $closingBalance,
            'start_date' => $startDate,
            'end_date' => $endDate
        ];
    }

    /**
     * Export report to PDF
     *
     * @param array $data
     * @param string $type
     * @return \Illuminate\Http\Response
     */
    public function exportToPdf(array $data, string $type)
    {
        $viewName = $this->getViewName($type);
        $fileName = $this->getFileName($type, $data);

        try {
            $pdf = Pdf::loadView($viewName, $data);
            return $pdf->download($fileName);
        } catch (\Exception $e) {
            throw new \RuntimeException("Failed to generate PDF: " . $e->getMessage());
        }
    }

    /**
     * Export report to Excel
     *
     * @param array $data
     * @param string $type
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportToExcel(array $data, string $type)
    {
        $fileName = $this->getFileName($type, $data, 'xlsx');
        
        try {
            return Excel::download(new LaporanKeuanganExport($data, $type), $fileName);
        } catch (\Exception $e) {
            throw new \RuntimeException("Failed to generate Excel: " . $e->getMessage());
        }
    }

    /**
     * Get view name based on report type
     *
     * @param string $type
     * @return string
     */
    private function getViewName(string $type): string
    {
        return match($type) {
            'kas-masuk' => 'laporan-keuangan.pdf.kas-masuk',
            'kas-keluar' => 'laporan-keuangan.pdf.kas-keluar',
            'arus-kas' => 'laporan-keuangan.pdf.arus-kas',
            default => 'laporan-keuangan.pdf.kas-masuk'
        };
    }

    /**
     * Get file name for export
     *
     * @param string $type
     * @param array $data
     * @param string $extension
     * @return string
     */
    private function getFileName(string $type, array $data, string $extension = 'pdf'): string
    {
        $typeName = match($type) {
            'kas-masuk' => 'Kas-Masuk',
            'kas-keluar' => 'Kas-Keluar',
            'arus-kas' => 'Arus-Kas',
            default => 'Laporan'
        };

        $startDate = $data['start_date'] ?? date('Y-m-d');
        $endDate = $data['end_date'] ?? date('Y-m-d');

        return "Laporan-{$typeName}-{$startDate}-{$endDate}.{$extension}";
    }
}
