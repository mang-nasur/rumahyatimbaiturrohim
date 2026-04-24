<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\LaporanKeuanganService;
use App\Models\Transaksi;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LaporanKeuanganServiceTest extends TestCase
{
    use RefreshDatabase;

    private LaporanKeuanganService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new LaporanKeuanganService();
    }

    public function test_get_laporan_kas_masuk_returns_correct_structure()
    {
        // Create test data
        Transaksi::factory()->create([
            'tanggal' => '2024-01-15',
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'kategori' => 'Donasi Individu',
            'jumlah' => 1000000
        ]);

        Transaksi::factory()->create([
            'tanggal' => '2024-01-20',
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'kategori' => 'Donasi Perusahaan',
            'jumlah' => 5000000
        ]);

        $result = $this->service->getLaporanKasMasuk('2024-01-01', '2024-01-31');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('transaksi', $result);
        $this->assertArrayHasKey('grouped', $result);
        $this->assertArrayHasKey('grand_total', $result);
        $this->assertArrayHasKey('start_date', $result);
        $this->assertArrayHasKey('end_date', $result);
        $this->assertEquals(2, $result['transaksi']->count());
        $this->assertEquals(6000000, $result['grand_total']);
    }

    public function test_get_laporan_kas_masuk_filters_by_category()
    {
        Transaksi::factory()->create([
            'tanggal' => '2024-01-15',
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'kategori' => 'Donasi Individu',
            'jumlah' => 1000000
        ]);

        Transaksi::factory()->create([
            'tanggal' => '2024-01-20',
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'kategori' => 'Donasi Perusahaan',
            'jumlah' => 5000000
        ]);

        $result = $this->service->getLaporanKasMasuk('2024-01-01', '2024-01-31', 'Donasi Individu');

        $this->assertEquals(1, $result['transaksi']->count());
        $this->assertEquals(1000000, $result['grand_total']);
        $this->assertEquals('Donasi Individu', $result['kategori']);
    }

    public function test_get_laporan_kas_masuk_groups_by_category()
    {
        Transaksi::factory()->create([
            'tanggal' => '2024-01-15',
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'kategori' => 'Donasi Individu',
            'jumlah' => 1000000
        ]);

        Transaksi::factory()->create([
            'tanggal' => '2024-01-16',
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'kategori' => 'Donasi Individu',
            'jumlah' => 500000
        ]);

        Transaksi::factory()->create([
            'tanggal' => '2024-01-20',
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'kategori' => 'Donasi Perusahaan',
            'jumlah' => 5000000
        ]);

        $result = $this->service->getLaporanKasMasuk('2024-01-01', '2024-01-31');

        $this->assertCount(2, $result['grouped']);
        $this->assertEquals(1500000, $result['grouped']['Donasi Individu']['subtotal']);
        $this->assertEquals(5000000, $result['grouped']['Donasi Perusahaan']['subtotal']);
    }

    public function test_get_laporan_kas_keluar_returns_correct_structure()
    {
        Transaksi::factory()->create([
            'tanggal' => '2024-01-15',
            'jenis' => Transaksi::JENIS_PENGELUARAN,
            'kategori' => 'Kebutuhan Anak',
            'jumlah' => 500000
        ]);

        Transaksi::factory()->create([
            'tanggal' => '2024-01-20',
            'jenis' => Transaksi::JENIS_PENGELUARAN,
            'kategori' => 'Operasional Panti',
            'jumlah' => 2000000
        ]);

        $result = $this->service->getLaporanKasKeluar('2024-01-01', '2024-01-31');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('transaksi', $result);
        $this->assertArrayHasKey('grouped', $result);
        $this->assertArrayHasKey('grand_total', $result);
        $this->assertEquals(2, $result['transaksi']->count());
        $this->assertEquals(2500000, $result['grand_total']);
    }

    public function test_get_laporan_kas_keluar_filters_by_category()
    {
        Transaksi::factory()->create([
            'tanggal' => '2024-01-15',
            'jenis' => Transaksi::JENIS_PENGELUARAN,
            'kategori' => 'Kebutuhan Anak',
            'jumlah' => 500000
        ]);

        Transaksi::factory()->create([
            'tanggal' => '2024-01-20',
            'jenis' => Transaksi::JENIS_PENGELUARAN,
            'kategori' => 'Operasional Panti',
            'jumlah' => 2000000
        ]);

        $result = $this->service->getLaporanKasKeluar('2024-01-01', '2024-01-31', 'Kebutuhan Anak');

        $this->assertEquals(1, $result['transaksi']->count());
        $this->assertEquals(500000, $result['grand_total']);
    }

    public function test_get_laporan_arus_kas_calculates_opening_balance()
    {
        // Transactions before period
        Transaksi::factory()->create([
            'tanggal' => '2023-12-15',
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'jumlah' => 10000000
        ]);

        Transaksi::factory()->create([
            'tanggal' => '2023-12-20',
            'jenis' => Transaksi::JENIS_PENGELUARAN,
            'jumlah' => 3000000
        ]);

        // Transactions in period
        Transaksi::factory()->create([
            'tanggal' => '2024-01-15',
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'jumlah' => 2000000
        ]);

        $result = $this->service->getLaporanArusKas('2024-01-01', '2024-01-31');

        $this->assertEquals(7000000, $result['opening_balance']);
    }

    public function test_get_laporan_arus_kas_calculates_running_balance()
    {
        Transaksi::factory()->create([
            'tanggal' => '2024-01-10',
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'jumlah' => 5000000
        ]);

        Transaksi::factory()->create([
            'tanggal' => '2024-01-15',
            'jenis' => Transaksi::JENIS_PENGELUARAN,
            'jumlah' => 2000000
        ]);

        Transaksi::factory()->create([
            'tanggal' => '2024-01-20',
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'jumlah' => 3000000
        ]);

        $result = $this->service->getLaporanArusKas('2024-01-01', '2024-01-31');

        $transaksiWithBalance = $result['transaksi'];
        
        $this->assertEquals(5000000, $transaksiWithBalance[0]['running_balance']);
        $this->assertEquals(3000000, $transaksiWithBalance[1]['running_balance']);
        $this->assertEquals(6000000, $transaksiWithBalance[2]['running_balance']);
    }

    public function test_get_laporan_arus_kas_calculates_closing_balance()
    {
        // Opening balance: 0
        Transaksi::factory()->create([
            'tanggal' => '2024-01-10',
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'jumlah' => 5000000
        ]);

        Transaksi::factory()->create([
            'tanggal' => '2024-01-15',
            'jenis' => Transaksi::JENIS_PENGELUARAN,
            'jumlah' => 2000000
        ]);

        $result = $this->service->getLaporanArusKas('2024-01-01', '2024-01-31');

        // Closing = Opening (0) + Penerimaan (5000000) - Pengeluaran (2000000)
        $this->assertEquals(3000000, $result['closing_balance']);
        $this->assertEquals(5000000, $result['total_penerimaan']);
        $this->assertEquals(2000000, $result['total_pengeluaran']);
    }

    public function test_get_laporan_arus_kas_returns_correct_structure()
    {
        Transaksi::factory()->create([
            'tanggal' => '2024-01-15',
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'jumlah' => 1000000
        ]);

        $result = $this->service->getLaporanArusKas('2024-01-01', '2024-01-31');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('opening_balance', $result);
        $this->assertArrayHasKey('transaksi', $result);
        $this->assertArrayHasKey('total_penerimaan', $result);
        $this->assertArrayHasKey('total_pengeluaran', $result);
        $this->assertArrayHasKey('closing_balance', $result);
        $this->assertArrayHasKey('start_date', $result);
        $this->assertArrayHasKey('end_date', $result);
    }

    public function test_get_laporan_kas_masuk_excludes_pengeluaran()
    {
        Transaksi::factory()->create([
            'tanggal' => '2024-01-15',
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'jumlah' => 1000000
        ]);

        Transaksi::factory()->create([
            'tanggal' => '2024-01-20',
            'jenis' => Transaksi::JENIS_PENGELUARAN,
            'jumlah' => 500000
        ]);

        $result = $this->service->getLaporanKasMasuk('2024-01-01', '2024-01-31');

        $this->assertEquals(1, $result['transaksi']->count());
        $this->assertEquals(1000000, $result['grand_total']);
    }

    public function test_get_laporan_kas_keluar_excludes_penerimaan()
    {
        Transaksi::factory()->create([
            'tanggal' => '2024-01-15',
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'jumlah' => 1000000
        ]);

        Transaksi::factory()->create([
            'tanggal' => '2024-01-20',
            'jenis' => Transaksi::JENIS_PENGELUARAN,
            'jumlah' => 500000
        ]);

        $result = $this->service->getLaporanKasKeluar('2024-01-01', '2024-01-31');

        $this->assertEquals(1, $result['transaksi']->count());
        $this->assertEquals(500000, $result['grand_total']);
    }

    public function test_get_laporan_arus_kas_orders_transactions_chronologically()
    {
        Transaksi::factory()->create([
            'tanggal' => '2024-01-20',
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'jumlah' => 3000000
        ]);

        Transaksi::factory()->create([
            'tanggal' => '2024-01-10',
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'jumlah' => 1000000
        ]);

        Transaksi::factory()->create([
            'tanggal' => '2024-01-15',
            'jenis' => Transaksi::JENIS_PENGELUARAN,
            'jumlah' => 500000
        ]);

        $result = $this->service->getLaporanArusKas('2024-01-01', '2024-01-31');

        $dates = $result['transaksi']->pluck('transaksi.tanggal')->map(fn($d) => $d->format('Y-m-d'));
        
        $this->assertEquals('2024-01-10', $dates[0]);
        $this->assertEquals('2024-01-15', $dates[1]);
        $this->assertEquals('2024-01-20', $dates[2]);
    }
}
