<?php

namespace App\Services;

use App\Models\AnakYatim;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class LaporanService
{
    /**
     * Get data for report based on type and filters
     */
    public function getData(string $type, array $filters): Collection
    {
        $query = $this->buildQuery($type, $filters);
        return $query->get();
    }

    /**
     * Build query based on report type and filters
     */
    private function buildQuery(string $type, array $filters): Builder
    {
        $query = AnakYatim::query();

        switch ($type) {
            case 'semua':
                // No additional filters
                break;

            case 'usia':
                if (isset($filters['min_age']) && isset($filters['max_age'])) {
                    $query->byAgeRange((int)$filters['min_age'], (int)$filters['max_age']);
                }
                break;

            case 'pendidikan':
                if (isset($filters['pendidikan'])) {
                    $query->byPendidikan($filters['pendidikan']);
                }
                break;

            case 'tahun_masuk':
                if (isset($filters['tahun'])) {
                    $query->byTahunMasuk((int)$filters['tahun']);
                }
                break;
        }

        return $query->orderBy('nama_lengkap');
    }

    /**
     * Format data for report display
     */
    public function formatData(Collection $data): array
    {
        return $data->map(function ($anak) {
            return [
                'Nama Lengkap'       => $anak->nama_lengkap,
                'Tempat, Tgl Lahir'  => $anak->tempat_lahir . ', ' . $anak->tanggal_lahir->format('d/m/Y'),
                'Usia'               => $anak->usia . ' tahun',
                'Jenis Kelamin'      => $anak->jenis_kelamin,
                'Alamat'             => $anak->alamat ?? '-',
                'NIK'                => $anak->nik ?? '-',
                'No KK'              => $anak->no_kk ?? '-',
                'Nama Ayah'          => $anak->nama_ayah ?? '-',
                'Status Ayah'        => $anak->status_ayah ?? '-',
                'Nama Ibu'           => $anak->nama_ibu ?? '-',
                'Status Ibu'         => $anak->status_ibu ?? '-',
                'No. Telepon Wali'   => $anak->nomor_telepon_wali ?? '-',
                'Sekolah (Sekarang)'  => $anak->kelas_sekarang,
                'Sekolah Saat Ini'    => $anak->sekolah_saat_ini ?? '-',
                'Kelas Saat Masuk'    => $anak->kelas_saat_masuk ?? '-',                'Tanggal Masuk'      => $anak->tanggal_masuk->format('d/m/Y'),
                'Tanggal Keluar'     => $anak->tanggal_keluar ? $anak->tanggal_keluar->format('d/m/Y') : '-',
                'Status'             => $anak->is_aktif ? 'Aktif' : 'Tidak Aktif',
            ];
        })->toArray();
    }

    /**
     * Get report title based on type and filters
     */
    public function getReportTitle(string $type, array $filters): string
    {
        switch ($type) {
            case 'semua':
                return 'Laporan Data Semua Anak Yatim';
            
            case 'usia':
                $min = $filters['min_age'] ?? 0;
                $max = $filters['max_age'] ?? 0;
                return "Laporan Data Anak Yatim Usia {$min}-{$max} Tahun";
            
            case 'pendidikan':
                $pendidikan = $filters['pendidikan'] ?? '';
                return "Laporan Data Anak Yatim Pendidikan {$pendidikan}";
            
            case 'tahun_masuk':
                $tahun = $filters['tahun'] ?? '';
                return "Laporan Data Anak Yatim Masuk Tahun {$tahun}";
            
            default:
                return 'Laporan Data Anak Yatim';
        }
    }
}
