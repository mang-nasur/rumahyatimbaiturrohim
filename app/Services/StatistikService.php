<?php

namespace App\Services;

use App\Models\AnakYatim;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class StatistikService
{
    /**
     * Get total number of children
     */
    public function getTotalAnak(): int
    {
        return AnakYatim::count();
    }

    /**
     * Get count by gender
     */
    public function getByGender(): array
    {
        $stats = AnakYatim::selectRaw('jenis_kelamin, COUNT(*) as total')
            ->groupBy('jenis_kelamin')
            ->pluck('total', 'jenis_kelamin')
            ->toArray();

        return [
            'Laki-laki' => $stats['Laki-laki'] ?? 0,
            'Perempuan' => $stats['Perempuan'] ?? 0,
        ];
    }

    /**
     * Get count by education level
     */
    public function getByPendidikan(): array
    {
        return AnakYatim::selectRaw('pendidikan_terakhir, COUNT(*) as total')
            ->whereNotNull('pendidikan_terakhir')
            ->groupBy('pendidikan_terakhir')
            ->pluck('total', 'pendidikan_terakhir')
            ->toArray();
    }

    /**
     * Get count by age group
     */
    public function getByAgeGroup(): array
    {
        $now = Carbon::now();
        
        return [
            '0-5 tahun' => AnakYatim::where('tanggal_lahir', '>=', $now->copy()->subYears(5))
                ->where('tanggal_lahir', '<=', $now)
                ->count(),
            '6-12 tahun' => AnakYatim::where('tanggal_lahir', '>=', $now->copy()->subYears(12))
                ->where('tanggal_lahir', '<', $now->copy()->subYears(5))
                ->count(),
            '13-17 tahun' => AnakYatim::where('tanggal_lahir', '>=', $now->copy()->subYears(17))
                ->where('tanggal_lahir', '<', $now->copy()->subYears(12))
                ->count(),
            '18+ tahun' => AnakYatim::where('tanggal_lahir', '<', $now->copy()->subYears(17))
                ->count(),
        ];
    }

    /**
     * Get recent entries
     */
    public function getRecentEntries(int $limit = 5): Collection
    {
        return AnakYatim::orderBy('tanggal_masuk', 'desc')
            ->limit($limit)
            ->get();
    }
}
