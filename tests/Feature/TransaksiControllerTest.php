<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class TransaksiControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test index method displays paginated list.
     */
    public function test_index_displays_paginated_list(): void
    {
        $user = User::factory()->create(['role' => 'bendahara']);
        
        Transaksi::factory()->count(20)->create();

        $response = $this->actingAs($user)->get(route('transaksi.index'));

        $response->assertStatus(200);
        $response->assertViewIs('transaksi.index');
        $response->assertViewHas('transaksi');
        $response->assertViewHas('kategoriOptions');
    }

    /**
     * Test index method with date range filter.
     */
    public function test_index_with_date_range_filter(): void
    {
        $user = User::factory()->create(['role' => 'bendahara']);
        
        Transaksi::factory()->create([
            'tanggal' => '2024-01-15',
            'keterangan' => 'January Transaction',
        ]);
        Transaksi::factory()->create([
            'tanggal' => '2024-03-15',
            'keterangan' => 'March Transaction',
        ]);

        $response = $this->actingAs($user)->get(route('transaksi.index', [
            'tanggal_dari' => '2024-01-01',
            'tanggal_sampai' => '2024-01-31',
        ]));

        $response->assertStatus(200);
        $response->assertSee('January Transaction');
        $response->assertDontSee('March Transaction');
    }

    /**
     * Test index method with jenis filter.
     */
    public function test_index_with_jenis_filter(): void
    {
        $user = User::factory()->create(['role' => 'bendahara']);
        
        Transaksi::factory()->create([
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'keterangan' => 'Receipt Transaction',
        ]);
        Transaksi::factory()->create([
            'jenis' => Transaksi::JENIS_PENGELUARAN,
            'keterangan' => 'Expense Transaction',
        ]);

        $response = $this->actingAs($user)->get(route('transaksi.index', [
            'jenis' => Transaksi::JENIS_PENERIMAAN,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Receipt Transaction');
        $response->assertDontSee('Expense Transaction');
    }

    /**
     * Test index method with kategori filter.
     */
    public function test_index_with_kategori_filter(): void
    {
        $user = User::factory()->create(['role' => 'bendahara']);
        
        Transaksi::factory()->create([
            'kategori' => 'Donasi Individu',
            'keterangan' => 'Individual Donation',
        ]);
        Transaksi::factory()->create([
            'kategori' => 'Kebutuhan Anak',
            'keterangan' => 'Child Needs',
        ]);

        $response = $this->actingAs($user)->get(route('transaksi.index', [
            'kategori' => 'Donasi Individu',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Individual Donation');
        $response->assertDontSee('Child Needs');
    }

    /**
     * Test index method with search parameter.
     */
    public function test_index_with_search_filters_results(): void
    {
        $user = User::factory()->create(['role' => 'bendahara']);
        
        Transaksi::factory()->create(['keterangan' => 'Donasi dari Pak Ahmad']);
        Transaksi::factory()->create(['keterangan' => 'Pembelian beras']);

        $response = $this->actingAs($user)->get(route('transaksi.index', ['search' => 'Donasi']));

        $response->assertStatus(200);
        $response->assertSee('Donasi dari Pak Ahmad');
        $response->assertDontSee('Pembelian beras');
    }

    /**
     * Test create method displays form.
     */
    public function test_create_displays_form(): void
    {
        $user = User::factory()->create(['role' => 'bendahara']);
        
        $response = $this->actingAs($user)->get(route('transaksi.create'));

        $response->assertStatus(200);
        $response->assertViewIs('transaksi.create');
        $response->assertViewHas('kategoriPenerimaan');
        $response->assertViewHas('kategoriPengeluaran');
    }

    /**
     * Test store method creates new penerimaan transaction.
     */
    public function test_store_creates_new_penerimaan_transaction(): void
    {
        $user = User::factory()->create(['role' => 'bendahara']);
        
        Storage::fake('public');

        $data = [
            'tanggal' => '2024-01-15',
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'kategori' => 'Donasi Individu',
            'jumlah' => 1000000,
            'keterangan' => 'Donasi dari Pak Ahmad',
        ];

        $response = $this->actingAs($user)->post(route('transaksi.store'), $data);

        $response->assertRedirect(route('transaksi.index'));
        $response->assertSessionHas('success', 'Transaksi berhasil ditambahkan.');
        $this->assertDatabaseHas('transaksi', [
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'kategori' => 'Donasi Individu',
            'jumlah' => 1000000,
        ]);
    }

    /**
     * Test store method creates new pengeluaran transaction.
     */
    public function test_store_creates_new_pengeluaran_transaction(): void
    {
        $user = User::factory()->create(['role' => 'bendahara']);
        
        Storage::fake('public');

        $data = [
            'tanggal' => '2024-01-15',
            'jenis' => Transaksi::JENIS_PENGELUARAN,
            'kategori' => 'Kebutuhan Anak',
            'jumlah' => 500000,
            'keterangan' => 'Pembelian buku pelajaran',
        ];

        $response = $this->actingAs($user)->post(route('transaksi.store'), $data);

        $response->assertRedirect(route('transaksi.index'));
        $response->assertSessionHas('success', 'Transaksi berhasil ditambahkan.');
        $this->assertDatabaseHas('transaksi', [
            'jenis' => Transaksi::JENIS_PENGELUARAN,
            'kategori' => 'Kebutuhan Anak',
            'jumlah' => 500000,
        ]);
    }

    /**
     * Test store method with file upload.
     */
    public function test_store_with_file_upload(): void
    {
        $user = User::factory()->create(['role' => 'bendahara']);
        
        Storage::fake('public');

        $file = UploadedFile::fake()->create('bukti.pdf', 1000, 'application/pdf');

        $data = [
            'tanggal' => '2024-01-15',
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'kategori' => 'Donasi Individu',
            'jumlah' => 1000000,
            'keterangan' => 'Donasi dengan bukti',
            'bukti_file' => $file,
        ];

        $response = $this->actingAs($user)->post(route('transaksi.store'), $data);

        $response->assertRedirect(route('transaksi.index'));
        
        $transaksi = Transaksi::where('keterangan', 'Donasi dengan bukti')->first();
        $this->assertNotNull($transaksi->bukti_file);
        Storage::disk('public')->assertExists($transaksi->bukti_file);
    }

    /**
     * Test show method displays detail.
     */
    public function test_show_displays_detail(): void
    {
        $user = User::factory()->create(['role' => 'bendahara']);
        
        $transaksi = Transaksi::factory()->create();

        $response = $this->actingAs($user)->get(route('transaksi.show', $transaksi));

        $response->assertStatus(200);
        $response->assertViewIs('transaksi.show');
        $response->assertViewHas('transaksi', $transaksi);
    }

    /**
     * Test edit method displays form with existing data.
     */
    public function test_edit_displays_form_with_data(): void
    {
        $user = User::factory()->create(['role' => 'bendahara']);
        
        $transaksi = Transaksi::factory()->create();

        $response = $this->actingAs($user)->get(route('transaksi.edit', $transaksi));

        $response->assertStatus(200);
        $response->assertViewIs('transaksi.edit');
        $response->assertViewHas('transaksi', $transaksi);
        $response->assertViewHas('kategoriPenerimaan');
        $response->assertViewHas('kategoriPengeluaran');
    }

    /**
     * Test update method updates existing record.
     */
    public function test_update_updates_existing_record(): void
    {
        $user = User::factory()->create(['role' => 'bendahara']);
        
        $transaksi = Transaksi::factory()->create([
            'keterangan' => 'Old Description',
            'jumlah' => 1000000,
        ]);

        $data = [
            'tanggal' => $transaksi->tanggal->format('Y-m-d'),
            'jenis' => $transaksi->jenis,
            'kategori' => $transaksi->kategori,
            'jumlah' => 2000000,
            'keterangan' => 'New Description',
        ];

        $response = $this->actingAs($user)->put(route('transaksi.update', $transaksi), $data);

        $response->assertRedirect(route('transaksi.index'));
        $response->assertSessionHas('success', 'Transaksi berhasil diperbarui.');
        $this->assertDatabaseHas('transaksi', [
            'id' => $transaksi->id,
            'keterangan' => 'New Description',
            'jumlah' => 2000000,
        ]);
    }

    /**
     * Test update method replaces old file.
     */
    public function test_update_replaces_old_file(): void
    {
        $user = User::factory()->create(['role' => 'bendahara']);
        
        Storage::fake('public');

        $oldFile = UploadedFile::fake()->create('old.pdf', 1000, 'application/pdf');
        $oldFilePath = $oldFile->storeAs('bukti', 'old_file.pdf', 'public');

        $transaksi = Transaksi::factory()->create([
            'bukti_file' => $oldFilePath,
        ]);

        $newFile = UploadedFile::fake()->create('new.pdf', 1000, 'application/pdf');

        $data = [
            'tanggal' => $transaksi->tanggal->format('Y-m-d'),
            'jenis' => $transaksi->jenis,
            'kategori' => $transaksi->kategori,
            'jumlah' => $transaksi->jumlah,
            'keterangan' => $transaksi->keterangan,
            'bukti_file' => $newFile,
        ];

        $response = $this->actingAs($user)->put(route('transaksi.update', $transaksi), $data);

        $response->assertRedirect(route('transaksi.index'));
        
        $transaksi->refresh();
        $this->assertNotEquals($oldFilePath, $transaksi->bukti_file);
        Storage::disk('public')->assertMissing($oldFilePath);
        Storage::disk('public')->assertExists($transaksi->bukti_file);
    }

    /**
     * Test destroy method deletes record and file.
     */
    public function test_destroy_deletes_record_and_file(): void
    {
        $user = User::factory()->create(['role' => 'bendahara']);
        
        Storage::fake('public');

        $file = UploadedFile::fake()->create('bukti.pdf', 1000, 'application/pdf');
        $filePath = $file->storeAs('bukti', 'test_file.pdf', 'public');

        $transaksi = Transaksi::factory()->create([
            'bukti_file' => $filePath,
        ]);

        $response = $this->actingAs($user)->delete(route('transaksi.destroy', $transaksi));

        $response->assertRedirect(route('transaksi.index'));
        $response->assertSessionHas('success', 'Transaksi berhasil dihapus.');
        $this->assertDatabaseMissing('transaksi', [
            'id' => $transaksi->id,
        ]);
        Storage::disk('public')->assertMissing($filePath);
    }

    /**
     * Test multiple filters applied simultaneously.
     */
    public function test_index_with_multiple_filters(): void
    {
        $user = User::factory()->create(['role' => 'bendahara']);
        
        Transaksi::factory()->create([
            'tanggal' => '2024-01-15',
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'kategori' => 'Donasi Individu',
            'keterangan' => 'Match All Filters',
        ]);
        Transaksi::factory()->create([
            'tanggal' => '2024-01-15',
            'jenis' => Transaksi::JENIS_PENGELUARAN,
            'kategori' => 'Kebutuhan Anak',
            'keterangan' => 'Different Type',
        ]);

        $response = $this->actingAs($user)->get(route('transaksi.index', [
            'tanggal_dari' => '2024-01-01',
            'tanggal_sampai' => '2024-01-31',
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'kategori' => 'Donasi Individu',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Match All Filters');
        $response->assertDontSee('Different Type');
    }

    /**
     * Test admin can access all transaksi features.
     */
    public function test_admin_can_access_all_features(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $transaksi = Transaksi::factory()->create();

        // Test create
        $response = $this->actingAs($admin)->get(route('transaksi.create'));
        $response->assertStatus(200);

        // Test store
        $data = [
            'tanggal' => '2024-01-15',
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'kategori' => 'Donasi Individu',
            'jumlah' => 1000000,
            'keterangan' => 'Test Admin Create',
        ];
        $response = $this->actingAs($admin)->post(route('transaksi.store'), $data);
        $response->assertRedirect(route('transaksi.index'));

        // Test edit
        $response = $this->actingAs($admin)->get(route('transaksi.edit', $transaksi));
        $response->assertStatus(200);

        // Test update
        $data = [
            'tanggal' => $transaksi->tanggal->format('Y-m-d'),
            'jenis' => $transaksi->jenis,
            'kategori' => $transaksi->kategori,
            'jumlah' => 2000000,
            'keterangan' => 'Updated by Admin',
        ];
        $response = $this->actingAs($admin)->put(route('transaksi.update', $transaksi), $data);
        $response->assertRedirect(route('transaksi.index'));

        // Test destroy
        $response = $this->actingAs($admin)->delete(route('transaksi.destroy', $transaksi));
        $response->assertRedirect(route('transaksi.index'));
    }

    /**
     * Test bendahara can access all transaksi CRUD operations.
     */
    public function test_bendahara_can_access_all_crud_operations(): void
    {
        $bendahara = User::factory()->create(['role' => 'bendahara']);
        $transaksi = Transaksi::factory()->create();

        // Test create
        $response = $this->actingAs($bendahara)->get(route('transaksi.create'));
        $response->assertStatus(200);

        // Test store
        $data = [
            'tanggal' => '2024-01-15',
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'kategori' => 'Donasi Individu',
            'jumlah' => 1000000,
            'keterangan' => 'Test Bendahara Create',
        ];
        $response = $this->actingAs($bendahara)->post(route('transaksi.store'), $data);
        $response->assertRedirect(route('transaksi.index'));

        // Test edit
        $response = $this->actingAs($bendahara)->get(route('transaksi.edit', $transaksi));
        $response->assertStatus(200);

        // Test update
        $data = [
            'tanggal' => $transaksi->tanggal->format('Y-m-d'),
            'jenis' => $transaksi->jenis,
            'kategori' => $transaksi->kategori,
            'jumlah' => 2000000,
            'keterangan' => 'Updated by Bendahara',
        ];
        $response = $this->actingAs($bendahara)->put(route('transaksi.update', $transaksi), $data);
        $response->assertRedirect(route('transaksi.index'));

        // Test destroy
        $response = $this->actingAs($bendahara)->delete(route('transaksi.destroy', $transaksi));
        $response->assertRedirect(route('transaksi.index'));
    }

    /**
     * Test staff cannot access transaksi CRUD operations.
     */
    public function test_staff_cannot_access_crud_operations(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $transaksi = Transaksi::factory()->create();

        // Test index (read) - should work based on route middleware
        $response = $this->actingAs($staff)->get(route('transaksi.index'));
        $response->assertStatus(200);

        // Test show (read) - should work based on route middleware
        $response = $this->actingAs($staff)->get(route('transaksi.show', $transaksi));
        $response->assertStatus(200);

        // Test create - should be forbidden
        $response = $this->actingAs($staff)->get(route('transaksi.create'));
        $response->assertStatus(403);

        // Test store - should be forbidden
        $data = [
            'tanggal' => '2024-01-15',
            'jenis' => Transaksi::JENIS_PENERIMAAN,
            'kategori' => 'Donasi Individu',
            'jumlah' => 1000000,
            'keterangan' => 'Test Staff Create',
        ];
        $response = $this->actingAs($staff)->post(route('transaksi.store'), $data);
        $response->assertStatus(403);

        // Test edit - should be forbidden
        $response = $this->actingAs($staff)->get(route('transaksi.edit', $transaksi));
        $response->assertStatus(403);

        // Test update - should be forbidden
        $data = [
            'tanggal' => $transaksi->tanggal->format('Y-m-d'),
            'jenis' => $transaksi->jenis,
            'kategori' => $transaksi->kategori,
            'jumlah' => 2000000,
            'keterangan' => 'Updated by Staff',
        ];
        $response = $this->actingAs($staff)->put(route('transaksi.update', $transaksi), $data);
        $response->assertStatus(403);

        // Test destroy - should be forbidden
        $response = $this->actingAs($staff)->delete(route('transaksi.destroy', $transaksi));
        $response->assertStatus(403);
    }
}
