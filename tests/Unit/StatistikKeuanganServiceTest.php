<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\StatistikKeuanganService;
use App\Models\Transaksi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class StatistikKeuanganServiceTest extends TestCase
{
    use RefreshDatabase;

    private StatistikKeuanganService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new StatistikKeuanganService();
    }

    /** @test */
    public function it_calculates_saldo_kas_correctly()
    {
        // Create penerimaan transactions
        Transaksi::factory()->create([
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'jumlah' => 1000000,
        ]);
        Transaksi::factory()->create([
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'jumlah' => 500000,
        ]);

        // Create pengeluaran transactions
        Transaksi::factory()->create([
            'jenis' => Transaksi::JENIS_PENGELUARAN,
            'jumlah' => 300000,
        ]);

        $saldo = $this->service->getSaldoKas();

        // Expected: 1000000 + 500000 - 300000 = 1200000
        $this->assertEquals(1200000, $saldo);
    }

    /** @test */
    public function it_returns_zero_saldo_when_no_transactions()
    {
        $saldo = $this->service->getSaldoKas();

        $this->assertEquals(0, $saldo);
    }

    /** @test */
    public function it_calculates_total_penerimaan_bulan_ini()
    {
        $now = Carbon::now();

        // Create transactions for current month
        Transaksi::factory()->create([
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'jumlah' => 1000000,
            'tanggal' => $now->copy()->startOfMonth(),
        ]);
        Transaksi::factory()->create([
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'jumlah' => 500000,
            'tanggal' => $now->copy()->endOfMonth(),
        ]);

        // Create transaction for previous month (should not be included)
        Transaksi::factory()->create([
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'jumlah' => 200000,
            'tanggal' => $now->copy()->subMonth(),
        ]);

        $total = $this->service->getTotalPenerimaanBulanIni();

        $this->assertEquals(1500000, $total);
    }

    /** @test */
    public function it_calculates_total_pengeluaran_bulan_ini()
    {
        $now = Carbon::now();

        // Create transactions for current month
        Transaksi::factory()->create([
            'jenis' => Transaksi::JENIS_PENGELUARAN,
            'jumlah' => 300000,
            'tanggal' => $now->copy()->startOfMonth(),
        ]);
        Transaksi::factory()->create([
            'jenis' => Transaksi::JENIS_PENGELUARAN,
            'jumlah' => 200000,
            'tanggal' => $now->copy()->endOfMonth(),
        ]);

        // Create transaction for previous month (should not be included)
        Transaksi::factory()->create([
            'jenis' => Transaksi::JENIS_PENGELUARAN,
            'jumlah' => 100000,
            'tanggal' => $now->copy()->subMonth(),
        ]);

        $total = $this->service->getTotalPengeluaranBulanIni();

        $this->assertEquals(500000, $total);
    }

    /** @test */
    public function it_generates_grafik_data_for_default_6_months()
    {
        $now = Carbon::now();

        // Create transactions for different months
        for ($i = 0; $i < 6; $i++) {
            $date = $now->copy()->subMonths($i);
            
            Transaksi::factory()->create([
                'jenis' => Transaksi::JENIS_PENERIMAAN,
                'jumlah' => 1000000,
                'tanggal' => $date,
            ]);
            
            Transaksi::factory()->create([
                'jenis' => Transaksi::JENIS_PENGELUARAN,
                'jumlah' => 500000,
                'tanggal' => $date,
            ]);
        }

        $grafik = $this->service->getGrafikPenerimaanVsPengeluaran();

        $this->assertIsArray($grafik);
        $this->assertArrayHasKey('labels', $grafik);
        $this->assertArrayHasKey('penerimaan', $grafik);
        $this->assertArrayHasKey('pengeluaran', $grafik);
        
        $this->assertCount(6, $grafik['labels']);
        $this->assertCount(6, $grafik['penerimaan']);
        $this->assertCount(6, $grafik['pengeluaran']);
    }

    /** @test */
    public function it_generates_grafik_data_for_custom_months()
    {
        $grafik = $this->service->getGrafikPenerimaanVsPengeluaran(3);

        $this->assertCount(3, $grafik['labels']);
        $this->assertCount(3, $grafik['penerimaan']);
        $this->assertCount(3, $grafik['pengeluaran']);
    }

    /** @test */
    public function it_formats_grafik_labels_correctly()
    {
        $grafik = $this->service->getGrafikPenerimaanVsPengeluaran(1);

        // Check that label is in format "Mon YYYY" (e.g., "Jan 2024")
        $this->assertMatchesRegularExpression('/^[A-Z][a-z]{2} \d{4}$/', $grafik['labels'][0]);
    }

    /** @test */
    public function it_retrieves_transaksi_terbaru_with_default_limit()
    {
        // Create 15 transactions
        for ($i = 0; $i < 15; $i++) {
            Transaksi::factory()->create([
                'tanggal' => Carbon::now()->subDays($i),
            ]);
        }

        $transaksi = $this->service->getTransaksiTerbaru();

        $this->assertCount(10, $transaksi);
    }

    /** @test */
    public function it_retrieves_transaksi_terbaru_with_custom_limit()
    {
        // Create 10 transactions
        for ($i = 0; $i < 10; $i++) {
            Transaksi::factory()->create([
                'tanggal' => Carbon::now()->subDays($i),
            ]);
        }

        $transaksi = $this->service->getTransaksiTerbaru(5);

        $this->assertCount(5, $transaksi);
    }

    /** @test */
    public function it_orders_transaksi_terbaru_by_date_desc()
    {
        $oldest = Transaksi::factory()->create([
            'tanggal' => Carbon::now()->subDays(5),
        ]);
        $newest = Transaksi::factory()->create([
            'tanggal' => Carbon::now(),
        ]);
        $middle = Transaksi::factory()->create([
            'tanggal' => Carbon::now()->subDays(2),
        ]);

        $transaksi = $this->service->getTransaksiTerbaru();

        $this->assertEquals($newest->id, $transaksi->first()->id);
        $this->assertEquals($oldest->id, $transaksi->last()->id);
    }

    /** @test */
    public function it_returns_empty_collection_when_no_transactions()
    {
        $transaksi = $this->service->getTransaksiTerbaru();

        $this->assertCount(0, $transaksi);
        $this->assertInstanceOf(Collection::class, $transaksi);
    }

    /** @test */
    public function it_excludes_pengeluaran_from_penerimaan_bulan_ini()
    {
        $now = Carbon::now();

        Transaksi::factory()->create([
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'jumlah' => 1000000,
            'tanggal' => $now,
        ]);
        
        Transaksi::factory()->create([
            'jenis' => Transaksi::JENIS_PENGELUARAN,
            'jumlah' => 500000,
            'tanggal' => $now,
        ]);

        $total = $this->service->getTotalPenerimaanBulanIni();

        $this->assertEquals(1000000, $total);
    }

    /** @test */
    public function it_excludes_penerimaan_from_pengeluaran_bulan_ini()
    {
        $now = Carbon::now();

        Transaksi::factory()->create([
            'jenis' => Transaksi::JENIS_PENGELUARAN,
            'jumlah' => 500000,
            'tanggal' => $now,
        ]);
        
        Transaksi::factory()->create([
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'jumlah' => 1000000,
            'tanggal' => $now,
        ]);

        $total = $this->service->getTotalPengeluaranBulanIni();

        $this->assertEquals(500000, $total);
    }
}
