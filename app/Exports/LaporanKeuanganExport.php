<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanKeuanganExport implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    protected array $data;
    protected string $type;

    public function __construct(array $data, string $type)
    {
        $this->data = $data;
        $this->type = $type;
    }

    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        if ($this->type === 'arus-kas') {
            return $this->collectionArusKas();
        }

        return $this->collectionKasMasukKeluar();
    }

    /**
     * Collection for kas masuk/keluar reports
     */
    private function collectionKasMasukKeluar(): Collection
    {
        $rows = collect();

        foreach ($this->data['grouped'] as $kategori => $group) {
            // Add category header
            $rows->push([
                'kategori' => $kategori,
                'tanggal' => '',
                'keterangan' => '',
                'jumlah' => ''
            ]);

            // Add transactions in category
            foreach ($group['transaksi'] as $transaksi) {
                $rows->push([
                    'kategori' => '',
                    'tanggal' => $transaksi->tanggal->format('d/m/Y'),
                    'keterangan' => $transaksi->keterangan,
                    'jumlah' => $transaksi->jumlah
                ]);
            }

            // Add subtotal
            $rows->push([
                'kategori' => '',
                'tanggal' => '',
                'keterangan' => 'Subtotal ' . $kategori,
                'jumlah' => $group['subtotal']
            ]);

            // Add empty row
            $rows->push([
                'kategori' => '',
                'tanggal' => '',
                'keterangan' => '',
                'jumlah' => ''
            ]);
        }

        // Add grand total
        $rows->push([
            'kategori' => '',
            'tanggal' => '',
            'keterangan' => 'TOTAL',
            'jumlah' => $this->data['grand_total']
        ]);

        return $rows;
    }

    /**
     * Collection for arus kas report
     */
    private function collectionArusKas(): Collection
    {
        $rows = collect();

        // Opening balance
        $rows->push([
            'tanggal' => '',
            'jenis' => '',
            'kategori' => 'Saldo Awal',
            'keterangan' => '',
            'jumlah' => '',
            'saldo' => $this->data['opening_balance']
        ]);

        // Transactions with running balance
        foreach ($this->data['transaksi'] as $item) {
            $transaksi = $item['transaksi'];
            $rows->push([
                'tanggal' => $transaksi->tanggal->format('d/m/Y'),
                'jenis' => ucfirst($transaksi->jenis),
                'kategori' => $transaksi->kategori,
                'keterangan' => $transaksi->keterangan,
                'jumlah' => $transaksi->jumlah,
                'saldo' => $item['running_balance']
            ]);
        }

        // Empty row
        $rows->push([
            'tanggal' => '',
            'jenis' => '',
            'kategori' => '',
            'keterangan' => '',
            'jumlah' => '',
            'saldo' => ''
        ]);

        // Summary
        $rows->push([
            'tanggal' => '',
            'jenis' => '',
            'kategori' => 'Total Penerimaan',
            'keterangan' => '',
            'jumlah' => $this->data['total_penerimaan'],
            'saldo' => ''
        ]);

        $rows->push([
            'tanggal' => '',
            'jenis' => '',
            'kategori' => 'Total Pengeluaran',
            'keterangan' => '',
            'jumlah' => $this->data['total_pengeluaran'],
            'saldo' => ''
        ]);

        $rows->push([
            'tanggal' => '',
            'jenis' => '',
            'kategori' => 'Saldo Akhir',
            'keterangan' => '',
            'jumlah' => '',
            'saldo' => $this->data['closing_balance']
        ]);

        return $rows;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        if ($this->type === 'arus-kas') {
            return [
                'Tanggal',
                'Jenis',
                'Kategori',
                'Keterangan',
                'Jumlah',
                'Saldo'
            ];
        }

        return [
            'Kategori',
            'Tanggal',
            'Keterangan',
            'Jumlah'
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return match($this->type) {
            'kas-masuk' => 'Laporan Kas Masuk',
            'kas-keluar' => 'Laporan Kas Keluar',
            'arus-kas' => 'Laporan Arus Kas',
            default => 'Laporan Keuangan'
        };
    }
}
