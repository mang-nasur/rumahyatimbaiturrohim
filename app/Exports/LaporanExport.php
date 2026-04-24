<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanExport implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    protected Collection $data;
    protected string $title;

    public function __construct(Collection $data, string $title)
    {
        $this->data = $data;
        $this->title = $title;
    }

    /**
     * Return collection of data
     */
    public function collection(): Collection
    {
        return $this->data->map(function ($anak) {
            return [
                $anak->nama_lengkap,
                $anak->tempat_lahir . ', ' . $anak->tanggal_lahir->format('d/m/Y'),
                $anak->usia . ' tahun',
                $anak->jenis_kelamin,
                $anak->alamat ?? '-',
                $anak->nik ?? '-',
                $anak->no_kk ?? '-',
                $anak->nama_ayah ?? '-',
                $anak->status_ayah ?? '-',
                $anak->nama_ibu ?? '-',
                $anak->status_ibu ?? '-',
                $anak->nomor_telepon_wali ?? '-',
                $anak->kelas_sekarang,
                $anak->sekolah_saat_ini ?? '-',
                $anak->kelas_saat_masuk ?? '-',
                $anak->tanggal_masuk->format('d/m/Y'),
                $anak->tanggal_keluar ? $anak->tanggal_keluar->format('d/m/Y') : '-',
                $anak->is_aktif ? 'Aktif' : 'Tidak Aktif',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama Lengkap',
            'Tempat, Tanggal Lahir',
            'Usia',
            'Jenis Kelamin',
            'Alamat',
            'NIK',
            'No KK',
            'Nama Ayah',
            'Status Ayah',
            'Nama Ibu',
            'Status Ibu',
            'No. Telepon Wali',
            'Sekolah (Sekarang)',
            'Sekolah Saat Ini',
            'Kelas Saat Masuk',
            'Tanggal Masuk',
            'Tanggal Keluar',
            'Status',
        ];
    }

    /**
     * Define sheet title
     */
    public function title(): string
    {
        return 'Laporan Anak Yatim';
    }

    /**
     * Apply styles to worksheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
