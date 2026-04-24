<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Exports\LaporanKeuanganExport;
use App\Models\Transaksi;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LaporanKeuanganExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_export_kas_masuk_has_correct_headings()
    {
        $data = [
            'transaksi' => collect(),
            'grouped' => collect(),
            'grand_total' => 0,
            'start_date' => '2024-01-01',
            'end_date' => '2024-01-31'
        ];

        $export = new LaporanKeuanganExport($data, 'kas-masuk');
        $headings = $export->headings();

        $this->assertEquals(['Kategori', 'Tanggal', 'Keterangan', 'Jumlah'], $headings);
    }

    public function test_export_kas_keluar_has_correct_headings()
    {
        $data = [
            'transaksi' => collect(),
            'grouped' => collect(),
            'grand_total' => 0,
            'start_date' => '2024-01-01',
            'end_date' => '2024-01-31'
        ];

        $export = new LaporanKeuanganExport($data, 'kas-keluar');
        $headings = $export->headings();

        $this->assertEquals(['Kategori', 'Tanggal', 'Keterangan', 'Jumlah'], $headings);
    }

    public function test_export_arus_kas_has_correct_headings()
    {
        $data = [
            'opening_balance' => 0,
            'transaksi' => collect(),
            'total_penerimaan' => 0,
            'total_pengeluaran' => 0,
            'closing_balance' => 0,
            'start_date' => '2024-01-01',
            'end_date' => '2024-01-31'
        ];

        $export = new LaporanKeuanganExport($data, 'arus-kas');
        $headings = $export->headings();

        $this->assertEquals(['Tanggal', 'Jenis', 'Kategori', 'Keterangan', 'Jumlah', 'Saldo'], $headings);
    }

    public function test_export_kas_masuk_has_correct_title()
    {
        $data = [
            'transaksi' => collect(),
            'grouped' => collect(),
            'grand_total' => 0,
            'start_date' => '2024-01-01',
            'end_date' => '2024-01-31'
        ];

        $export = new LaporanKeuanganExport($data, 'kas-masuk');
        
        $this->assertEquals('Laporan Kas Masuk', $export->title());
    }

    public function test_export_kas_keluar_has_correct_title()
    {
        $data = [
            'transaksi' => collect(),
            'grouped' => collect(),
            'grand_total' => 0,
            'start_date' => '2024-01-01',
            'end_date' => '2024-01-31'
        ];

        $export = new LaporanKeuanganExport($data, 'kas-keluar');
        
        $this->assertEquals('Laporan Kas Keluar', $export->title());
    }

    public function test_export_arus_kas_has_correct_title()
    {
        $data = [
            'opening_balance' => 0,
            'transaksi' => collect(),
            'total_penerimaan' => 0,
            'total_pengeluaran' => 0,
            'closing_balance' => 0,
            'start_date' => '2024-01-01',
            'end_date' => '2024-01-31'
        ];

        $export = new LaporanKeuanganExport($data, 'arus-kas');
        
        $this->assertEquals('Laporan Arus Kas', $export->title());
    }

    public function test_export_kas_masuk_collection_includes_grouped_data()
    {
        $transaksi1 = Transaksi::factory()->make([
            'tanggal' => '2024-01-15',
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'kategori' => 'Donasi Individu',
            'keterangan' => 'Donasi dari Bapak A',
            'jumlah' => 1000000
        ]);

        $transaksi2 = Transaksi::factory()->make([
            'tanggal' => '2024-01-20',
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'kategori' => 'Donasi Individu',
            'keterangan' => 'Donasi dari Ibu B',
            'jumlah' => 500000
        ]);

        $grouped = collect([
            'Donasi Individu' => [
                'transaksi' => collect([$transaksi1, $transaksi2]),
                'subtotal' => 1500000
            ]
        ]);

        $data = [
            'transaksi' => collect([$transaksi1, $transaksi2]),
            'grouped' => $grouped,
            'grand_total' => 1500000,
            'start_date' => '2024-01-01',
            'end_date' => '2024-01-31'
        ];

        $export = new LaporanKeuanganExport($data, 'kas-masuk');
        $collection = $export->collection();

        $this->assertGreaterThan(0, $collection->count());
        
        // Check that collection includes category header
        $firstRow = $collection->first();
        $this->assertEquals('Donasi Individu', $firstRow['kategori']);
    }

    public function test_export_arus_kas_collection_includes_opening_balance()
    {
        $data = [
            'opening_balance' => 5000000,
            'transaksi' => collect(),
            'total_penerimaan' => 0,
            'total_pengeluaran' => 0,
            'closing_balance' => 5000000,
            'start_date' => '2024-01-01',
            'end_date' => '2024-01-31'
        ];

        $export = new LaporanKeuanganExport($data, 'arus-kas');
        $collection = $export->collection();

        $firstRow = $collection->first();
        $this->assertEquals('Saldo Awal', $firstRow['kategori']);
        $this->assertEquals(5000000, $firstRow['saldo']);
    }

    public function test_export_arus_kas_collection_includes_summary()
    {
        $data = [
            'opening_balance' => 0,
            'transaksi' => collect(),
            'total_penerimaan' => 5000000,
            'total_pengeluaran' => 2000000,
            'closing_balance' => 3000000,
            'start_date' => '2024-01-01',
            'end_date' => '2024-01-31'
        ];

        $export = new LaporanKeuanganExport($data, 'arus-kas');
        $collection = $export->collection();

        // Check that summary rows are included
        $summaryRows = $collection->filter(function ($row) {
            return in_array($row['kategori'], ['Total Penerimaan', 'Total Pengeluaran', 'Saldo Akhir']);
        });

        $this->assertEquals(3, $summaryRows->count());
    }

    public function test_export_styles_makes_header_bold()
    {
        $data = [
            'transaksi' => collect(),
            'grouped' => collect(),
            'grand_total' => 0,
            'start_date' => '2024-01-01',
            'end_date' => '2024-01-31'
        ];

        $export = new LaporanKeuanganExport($data, 'kas-masuk');
        
        // Create a mock worksheet
        $worksheet = $this->createMock(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::class);
        $styles = $export->styles($worksheet);

        $this->assertArrayHasKey(1, $styles);
        $this->assertTrue($styles[1]['font']['bold']);
    }
}
