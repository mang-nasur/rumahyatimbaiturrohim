<?php

namespace Tests\Unit;

use App\Models\Transaksi;
use App\Services\TransaksiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TransaksiServiceTest extends TestCase
{
    use RefreshDatabase;

    protected TransaksiService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new TransaksiService();
        Storage::fake('public');
    }

    // createTransaksi tests
    public function test_creates_transaction_without_file(): void
    {
        $data = [
            'tanggal' => '2024-01-15',
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'kategori' => 'Donasi Individu',
            'jumlah' => 500000,
            'keterangan' => 'Donasi dari Bapak Ahmad',
        ];

        $transaksi = $this->service->createTransaksi($data);

        $this->assertInstanceOf(Transaksi::class, $transaksi);
        $this->assertEquals('2024-01-15', $transaksi->tanggal->format('Y-m-d'));
        $this->assertEquals('penerimaan', $transaksi->jenis);
        $this->assertEquals('Donasi Individu', $transaksi->kategori);
        $this->assertEquals('500000.00', $transaksi->jumlah);
        $this->assertEquals('Donasi dari Bapak Ahmad', $transaksi->keterangan);
        $this->assertNull($transaksi->bukti_file);

        $this->assertDatabaseHas('transaksi', [
            'jenis' => 'penerimaan',
            'kategori' => 'Donasi Individu',
            'jumlah' => 500000,
        ]);
    }

    public function test_creates_transaction_with_file_upload(): void
    {
        $file = UploadedFile::fake()->create('bukti.pdf', 1000);
        
        $data = [
            'tanggal' => '2024-01-15',
            'jenis' => Transaksi::JENIS_PENGELUARAN,
            'kategori' => 'Kebutuhan Anak',
            'jumlah' => 250000,
            'keterangan' => 'Pembelian buku',
            'bukti_file' => $file,
        ];

        $transaksi = $this->service->createTransaksi($data);

        $this->assertNotNull($transaksi->bukti_file);
        $this->assertStringContainsString('bukti/', $transaksi->bukti_file);

        Storage::disk('public')->assertExists($transaksi->bukti_file);
    }

    public function test_generates_unique_filename_for_uploaded_file(): void
    {
        $file1 = UploadedFile::fake()->create('bukti.pdf', 1000);
        $file2 = UploadedFile::fake()->create('bukti.pdf', 1000);
        
        $data1 = [
            'tanggal' => '2024-01-15',
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'kategori' => 'Donasi Individu',
            'jumlah' => 100000,
            'keterangan' => 'Test 1',
            'bukti_file' => $file1,
        ];

        $data2 = [
            'tanggal' => '2024-01-16',
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'kategori' => 'Donasi Individu',
            'jumlah' => 200000,
            'keterangan' => 'Test 2',
            'bukti_file' => $file2,
        ];

        $transaksi1 = $this->service->createTransaksi($data1);
        $transaksi2 = $this->service->createTransaksi($data2);

        $this->assertNotEquals($transaksi1->bukti_file, $transaksi2->bukti_file);
        
        Storage::disk('public')->assertExists($transaksi1->bukti_file);
        Storage::disk('public')->assertExists($transaksi2->bukti_file);
    }

    // updateTransaksi tests
    public function test_updates_transaction_without_changing_file(): void
    {
        $transaksi = Transaksi::factory()->create([
            'jumlah' => 100000,
            'keterangan' => 'Original description',
        ]);

        $data = [
            'tanggal' => $transaksi->tanggal->format('Y-m-d'),
            'jenis' => $transaksi->jenis,
            'kategori' => $transaksi->kategori,
            'jumlah' => 150000,
            'keterangan' => 'Updated description',
        ];

        $updated = $this->service->updateTransaksi($transaksi, $data);

        $this->assertEquals('150000.00', $updated->jumlah);
        $this->assertEquals('Updated description', $updated->keterangan);

        $this->assertDatabaseHas('transaksi', [
            'id' => $transaksi->id,
            'jumlah' => 150000,
            'keterangan' => 'Updated description',
        ]);
    }

    public function test_updates_transaction_and_replaces_file(): void
    {
        $oldFile = UploadedFile::fake()->create('old.pdf', 1000);
        
        $transaksi = Transaksi::factory()->create();
        $oldPath = $oldFile->storeAs('bukti', 'old_file.pdf', 'public');
        $transaksi->update(['bukti_file' => $oldPath]);

        $newFile = UploadedFile::fake()->create('new.pdf', 1000);
        
        $data = [
            'tanggal' => $transaksi->tanggal->format('Y-m-d'),
            'jenis' => $transaksi->jenis,
            'kategori' => $transaksi->kategori,
            'jumlah' => $transaksi->jumlah,
            'keterangan' => $transaksi->keterangan,
            'bukti_file' => $newFile,
        ];

        $updated = $this->service->updateTransaksi($transaksi, $data);

        $this->assertNotEquals($oldPath, $updated->bukti_file);
        
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($updated->bukti_file);
    }

    public function test_keeps_existing_file_when_no_new_file_provided(): void
    {
        $file = UploadedFile::fake()->create('bukti.pdf', 1000);
        
        $transaksi = Transaksi::factory()->create();
        $filePath = $file->storeAs('bukti', 'existing.pdf', 'public');
        $transaksi->update(['bukti_file' => $filePath]);

        $data = [
            'tanggal' => $transaksi->tanggal->format('Y-m-d'),
            'jenis' => $transaksi->jenis,
            'kategori' => $transaksi->kategori,
            'jumlah' => 200000,
            'keterangan' => 'Updated without file change',
        ];

        $updated = $this->service->updateTransaksi($transaksi, $data);

        $this->assertEquals($filePath, $updated->bukti_file);
        Storage::disk('public')->assertExists($filePath);
    }

    // deleteTransaksi tests
    public function test_deletes_transaction_without_file(): void
    {
        $transaksi = Transaksi::factory()->create(['bukti_file' => null]);
        $id = $transaksi->id;

        $result = $this->service->deleteTransaksi($transaksi);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('transaksi', ['id' => $id]);
    }

    public function test_deletes_transaction_and_associated_file(): void
    {
        $file = UploadedFile::fake()->create('bukti.pdf', 1000);
        
        $transaksi = Transaksi::factory()->create();
        $filePath = $file->storeAs('bukti', 'to_delete.pdf', 'public');
        $transaksi->update(['bukti_file' => $filePath]);

        Storage::disk('public')->assertExists($filePath);

        $result = $this->service->deleteTransaksi($transaksi);

        $this->assertTrue($result);
        Storage::disk('public')->assertMissing($filePath);
        $this->assertDatabaseMissing('transaksi', ['id' => $transaksi->id]);
    }

    // getSaldo tests
    public function test_returns_zero_when_no_transactions_exist(): void
    {
        $saldo = $this->service->getSaldo();

        $this->assertEquals(0.0, $saldo);
    }

    public function test_calculates_balance_with_only_receipts(): void
    {
        Transaksi::factory()->create([
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'jumlah' => 500000,
        ]);
        
        Transaksi::factory()->create([
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'jumlah' => 300000,
        ]);

        $saldo = $this->service->getSaldo();

        $this->assertEquals(800000.0, $saldo);
    }

    public function test_calculates_balance_with_only_expenses(): void
    {
        Transaksi::factory()->create([
            'jenis' => Transaksi::JENIS_PENGELUARAN,
            'jumlah' => 200000,
        ]);
        
        Transaksi::factory()->create([
            'jenis' => Transaksi::JENIS_PENGELUARAN,
            'jumlah' => 150000,
        ]);

        $saldo = $this->service->getSaldo();

        $this->assertEquals(-350000.0, $saldo);
    }

    public function test_calculates_balance_with_mixed_transactions(): void
    {
        Transaksi::factory()->create([
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'jumlah' => 1000000,
        ]);
        
        Transaksi::factory()->create([
            'jenis' => Transaksi::JENIS_PENGELUARAN,
            'jumlah' => 300000,
        ]);
        
        Transaksi::factory()->create([
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'jumlah' => 500000,
        ]);
        
        Transaksi::factory()->create([
            'jenis' => Transaksi::JENIS_PENGELUARAN,
            'jumlah' => 200000,
        ]);

        $saldo = $this->service->getSaldo();

        $this->assertEquals(1000000.0, $saldo); // 1500000 - 500000
    }

    public function test_handles_decimal_amounts_correctly(): void
    {
        Transaksi::factory()->create([
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'jumlah' => 1000.50,
        ]);
        
        Transaksi::factory()->create([
            'jenis' => Transaksi::JENIS_PENGELUARAN,
            'jumlah' => 500.25,
        ]);

        $saldo = $this->service->getSaldo();

        $this->assertEquals(500.25, $saldo);
    }

    // getTransaksiWithFilters tests
    public function test_returns_all_transactions_without_filters(): void
    {
        Transaksi::factory()->count(5)->create();

        $query = $this->service->getTransaksiWithFilters([]);
        $results = $query->get();

        $this->assertCount(5, $results);
    }

    public function test_filters_by_date_range(): void
    {
        Transaksi::factory()->create(['tanggal' => '2024-01-10']);
        Transaksi::factory()->create(['tanggal' => '2024-01-15']);
        Transaksi::factory()->create(['tanggal' => '2024-01-20']);
        Transaksi::factory()->create(['tanggal' => '2024-01-25']);

        $filters = [
            'tanggal_dari' => '2024-01-12',
            'tanggal_sampai' => '2024-01-22',
        ];

        $query = $this->service->getTransaksiWithFilters($filters);
        $results = $query->get();

        $this->assertCount(2, $results);
    }

    public function test_filters_by_jenis(): void
    {
        Transaksi::factory()->count(3)->create(['jenis' => Transaksi::JENIS_PENERIMAAN]);
        Transaksi::factory()->count(2)->create(['jenis' => Transaksi::JENIS_PENGELUARAN]);

        $filters = ['jenis' => Transaksi::JENIS_PENERIMAAN];

        $query = $this->service->getTransaksiWithFilters($filters);
        $results = $query->get();

        $this->assertCount(3, $results);
        $results->each(function ($t) {
            $this->assertEquals(Transaksi::JENIS_PENERIMAAN, $t->jenis);
        });
    }

    public function test_filters_by_kategori(): void
    {
        Transaksi::factory()->count(2)->create(['kategori' => 'Donasi Individu']);
        Transaksi::factory()->count(3)->create(['kategori' => 'Donasi Perusahaan']);

        $filters = ['kategori' => 'Donasi Individu'];

        $query = $this->service->getTransaksiWithFilters($filters);
        $results = $query->get();

        $this->assertCount(2, $results);
        $results->each(function ($t) {
            $this->assertEquals('Donasi Individu', $t->kategori);
        });
    }

    public function test_searches_by_keterangan(): void
    {
        Transaksi::factory()->create(['keterangan' => 'Donasi untuk pendidikan']);
        Transaksi::factory()->create(['keterangan' => 'Pembelian buku']);
        Transaksi::factory()->create(['keterangan' => 'Donasi untuk kesehatan']);

        $filters = ['search' => 'pendidikan'];

        $query = $this->service->getTransaksiWithFilters($filters);
        $results = $query->get();

        $this->assertCount(1, $results);
        $this->assertStringContainsString('pendidikan', $results->first()->keterangan);
    }

    public function test_applies_multiple_filters_simultaneously(): void
    {
        Transaksi::factory()->create([
            'tanggal' => '2024-01-15',
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'kategori' => 'Donasi Individu',
            'keterangan' => 'Donasi untuk pendidikan',
        ]);
        
        Transaksi::factory()->create([
            'tanggal' => '2024-01-16',
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'kategori' => 'Donasi Individu',
            'keterangan' => 'Donasi untuk kesehatan',
        ]);
        
        Transaksi::factory()->create([
            'tanggal' => '2024-01-17',
            'jenis' => Transaksi::JENIS_PENGELUARAN,
            'kategori' => 'Kebutuhan Anak',
            'keterangan' => 'Pembelian buku',
        ]);

        $filters = [
            'tanggal_dari' => '2024-01-14',
            'tanggal_sampai' => '2024-01-16',
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'kategori' => 'Donasi Individu',
            'search' => 'pendidikan',
        ];

        $query = $this->service->getTransaksiWithFilters($filters);
        $results = $query->get();

        $this->assertCount(1, $results);
        $this->assertEquals('Donasi untuk pendidikan', $results->first()->keterangan);
    }

    public function test_orders_results_by_date_descending(): void
    {
        Transaksi::factory()->create(['tanggal' => '2024-01-10', 'jumlah' => 100]);
        Transaksi::factory()->create(['tanggal' => '2024-01-20', 'jumlah' => 200]);
        Transaksi::factory()->create(['tanggal' => '2024-01-15', 'jumlah' => 150]);

        $query = $this->service->getTransaksiWithFilters([]);
        $results = $query->get();

        $this->assertEquals('2024-01-20', $results->first()->tanggal->format('Y-m-d'));
        $this->assertEquals('2024-01-10', $results->last()->tanggal->format('Y-m-d'));
    }
}
