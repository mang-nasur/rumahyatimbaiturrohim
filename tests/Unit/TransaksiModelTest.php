<?php

namespace Tests\Unit;

use App\Models\Transaksi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransaksiModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_transaksi_model_has_correct_fillable_fields(): void
    {
        $fillable = (new Transaksi())->getFillable();
        
        $this->assertContains('tanggal', $fillable);
        $this->assertContains('jenis', $fillable);
        $this->assertContains('kategori', $fillable);
        $this->assertContains('jumlah', $fillable);
        $this->assertContains('keterangan', $fillable);
        $this->assertContains('bukti_file', $fillable);
    }

    public function test_transaksi_model_has_correct_casts(): void
    {
        $transaksi = new Transaksi();
        $casts = $transaksi->getCasts();
        
        $this->assertEquals('date', $casts['tanggal']);
        $this->assertEquals('decimal:2', $casts['jumlah']);
    }

    public function test_transaksi_constants_are_defined(): void
    {
        $this->assertEquals('penerimaan', Transaksi::JENIS_PENERIMAAN);
        $this->assertEquals('pengeluaran', Transaksi::JENIS_PENGELUARAN);
        
        $this->assertIsArray(Transaksi::KATEGORI_PENERIMAAN);
        $this->assertCount(4, Transaksi::KATEGORI_PENERIMAAN);
        
        $this->assertIsArray(Transaksi::KATEGORI_PENGELUARAN);
        $this->assertCount(5, Transaksi::KATEGORI_PENGELUARAN);
    }

    public function test_is_penerimaan_returns_true_for_receipt(): void
    {
        $transaksi = Transaksi::factory()->penerimaan()->make();
        
        $this->assertTrue($transaksi->isPenerimaan());
        $this->assertFalse($transaksi->isPengeluaran());
    }

    public function test_is_pengeluaran_returns_true_for_expense(): void
    {
        $transaksi = Transaksi::factory()->pengeluaran()->make();
        
        $this->assertTrue($transaksi->isPengeluaran());
        $this->assertFalse($transaksi->isPenerimaan());
    }

    public function test_formatted_jumlah_attribute_returns_rupiah_format(): void
    {
        $transaksi = Transaksi::factory()->make(['jumlah' => 1000000]);
        
        $this->assertEquals('Rp 1.000.000', $transaksi->formatted_jumlah);
    }

    public function test_scope_penerimaan_filters_receipts_only(): void
    {
        Transaksi::factory()->penerimaan()->create();
        Transaksi::factory()->pengeluaran()->create();
        
        $receipts = Transaksi::penerimaan()->get();
        
        $this->assertCount(1, $receipts);
        $this->assertTrue($receipts->first()->isPenerimaan());
    }

    public function test_scope_pengeluaran_filters_expenses_only(): void
    {
        Transaksi::factory()->penerimaan()->create();
        Transaksi::factory()->pengeluaran()->create();
        
        $expenses = Transaksi::pengeluaran()->get();
        
        $this->assertCount(1, $expenses);
        $this->assertTrue($expenses->first()->isPengeluaran());
    }

    public function test_scope_by_date_range_filters_correctly(): void
    {
        Transaksi::factory()->create(['tanggal' => '2024-01-15']);
        Transaksi::factory()->create(['tanggal' => '2024-02-15']);
        Transaksi::factory()->create(['tanggal' => '2024-03-15']);
        
        $filtered = Transaksi::byDateRange('2024-02-01', '2024-02-28')->get();
        
        $this->assertCount(1, $filtered);
        $this->assertEquals('2024-02-15', $filtered->first()->tanggal->format('Y-m-d'));
    }

    public function test_scope_by_kategori_filters_correctly(): void
    {
        Transaksi::factory()->create(['kategori' => 'Donasi Individu']);
        Transaksi::factory()->create(['kategori' => 'Kebutuhan Anak']);
        
        $filtered = Transaksi::byKategori('Donasi Individu')->get();
        
        $this->assertCount(1, $filtered);
        $this->assertEquals('Donasi Individu', $filtered->first()->kategori);
    }

    public function test_scope_search_filters_by_keterangan(): void
    {
        Transaksi::factory()->create(['keterangan' => 'Donasi dari Bapak Ahmad']);
        Transaksi::factory()->create(['keterangan' => 'Pembelian beras']);
        
        $filtered = Transaksi::search('Donasi')->get();
        
        $this->assertCount(1, $filtered);
        $this->assertStringContainsString('Donasi', $filtered->first()->keterangan);
    }

    public function test_factory_creates_valid_transaksi(): void
    {
        $transaksi = Transaksi::factory()->create();
        
        $this->assertDatabaseHas('transaksi', [
            'id' => $transaksi->id,
        ]);
        
        $this->assertNotNull($transaksi->tanggal);
        $this->assertNotNull($transaksi->jenis);
        $this->assertNotNull($transaksi->kategori);
        $this->assertNotNull($transaksi->jumlah);
        $this->assertNotNull($transaksi->keterangan);
    }

    public function test_factory_penerimaan_state_creates_receipt(): void
    {
        $transaksi = Transaksi::factory()->penerimaan()->create();
        
        $this->assertEquals(Transaksi::JENIS_PENERIMAAN, $transaksi->jenis);
        $this->assertContains($transaksi->kategori, Transaksi::KATEGORI_PENERIMAAN);
    }

    public function test_factory_pengeluaran_state_creates_expense(): void
    {
        $transaksi = Transaksi::factory()->pengeluaran()->create();
        
        $this->assertEquals(Transaksi::JENIS_PENGELUARAN, $transaksi->jenis);
        $this->assertContains($transaksi->kategori, Transaksi::KATEGORI_PENGELUARAN);
    }

    public function test_factory_with_bukti_file_state_adds_file(): void
    {
        $transaksi = Transaksi::factory()->withBuktiFile()->create();
        
        $this->assertNotNull($transaksi->bukti_file);
        $this->assertStringContainsString('bukti/', $transaksi->bukti_file);
    }
}
