<?php

namespace App\Http\Controllers;

use App\Services\StatistikKeuanganService;
use Illuminate\View\View;

class DashboardKeuanganController extends Controller
{
    public function __construct(
        private StatistikKeuanganService $statistikKeuanganService
    ) {}

    /**
     * Display financial dashboard with statistics
     */
    public function index(): View
    {
        $stats = [
            'saldo_kas' => $this->statistikKeuanganService->getSaldoKas(),
            'total_penerimaan_bulan_ini' => $this->statistikKeuanganService->getTotalPenerimaanBulanIni(),
            'total_pengeluaran_bulan_ini' => $this->statistikKeuanganService->getTotalPengeluaranBulanIni(),
            'grafik_data' => $this->statistikKeuanganService->getGrafikPenerimaanVsPengeluaran(6),
            'transaksi_terbaru' => $this->statistikKeuanganService->getTransaksiTerbaru(10)
        ];

        return view('keuangan.dashboard', compact('stats'));
    }
}
