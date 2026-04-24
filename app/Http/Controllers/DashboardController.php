<?php

namespace App\Http\Controllers;

use App\Models\AnakYatim;
use App\Models\Absensi;
use App\Services\StatistikService;
use App\Services\StatistikKeuanganService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private StatistikService $statistikService,
        private StatistikKeuanganService $statistikKeuanganService
    ) {}

    /**
     * Display dashboard with statistics
     */
    public function index(): View
    {
        $user = auth()->user();

        // Orang tua punya dashboard sendiri yang lebih sederhana
        if ($user->isOrangTua()) {
            $anak = $user->anakYatim;

            // Absensi bulan ini untuk anak ini
            $bulanIni = (int) now()->format('n');
            $tahunIni = (int) now()->format('Y');

            $absensibulanIni = $anak
                ? \App\Models\Absensi::where('anak_yatim_id', $anak->id)
                    ->where('bulan', $bulanIni)
                    ->where('tahun', $tahunIni)
                    ->first()
                : null;

            $riwayatAbsensi = $anak
                ? $anak->absensi()->orderByDesc('tahun')->orderByDesc('bulan')->take(6)->get()
                : collect();

            return view('dashboard.orang-tua', compact('anak', 'absensibulanIni', 'riwayatAbsensi'));
        }

        $stats = [
            'total_anak' => $this->statistikService->getTotalAnak(),
            'by_gender' => $this->statistikService->getByGender(),
            'by_pendidikan' => $this->statistikService->getByPendidikan(),
            'by_age_group' => $this->statistikService->getByAgeGroup(),
            'recent_entries' => $this->statistikService->getRecentEntries(5)
        ];

        $keuangan = [
            'saldo_kas' => $this->statistikKeuanganService->getSaldoKas(),
            'total_penerimaan_bulan_ini' => $this->statistikKeuanganService->getTotalPenerimaanBulanIni(),
            'total_pengeluaran_bulan_ini' => $this->statistikKeuanganService->getTotalPengeluaranBulanIni(),
        ];

        // Statistik absensi bulan ini
        $bulanIni = (int) now()->format('n');
        $tahunIni = (int) now()->format('Y');

        $absensi = [
            'pending'        => \App\Models\Absensi::periode($bulanIni, $tahunIni)->pending()->count(),
            'disetujui'      => \App\Models\Absensi::periode($bulanIni, $tahunIni)->disetujui()->count(),
            'tidak_hadir_3x' => AnakYatim::aktif()
                ->with(['absensi' => fn($q) => $q->where('status', 'disetujui')])
                ->get()
                ->filter(fn($a) => $a->getTidakHadirBerturut() >= 3)
                ->count(),
        ];

        return view('dashboard.index', compact('stats', 'keuangan', 'absensi'));
    }
}
