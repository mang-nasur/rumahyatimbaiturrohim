<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\AnakYatim;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexViewTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test index view contains all required elements.
     */
    public function test_index_view_contains_required_elements(): void
    {
        AnakYatim::factory()->count(3)->create();

        $response = $this->get(route('anak-yatim.index'));

        $response->assertStatus(200);
        
        // Check for search form
        $response->assertSee('Pencarian');
        $response->assertSee('Cari nama anak, ayah, atau ibu...');
        
        // Check for filter dropdowns
        $response->assertSee('Usia Min');
        $response->assertSee('Usia Max');
        $response->assertSee('Pendidikan');
        $response->assertSee('Tahun Masuk');
        
        // Check for table headers
        $response->assertSee('Nama');
        $response->assertSee('Usia');
        $response->assertSee('Jenis Kelamin');
        $response->assertSee('Pendidikan');
        $response->assertSee('Tanggal Masuk');
        $response->assertSee('Aksi');
        
        // Check for action buttons
        $response->assertSee('Lihat Detail');
        $response->assertSee('Edit');
        $response->assertSee('Hapus');
    }

    /**
     * Test index view shows empty message when no data.
     */
    public function test_index_view_shows_empty_message(): void
    {
        $response = $this->get(route('anak-yatim.index'));

        $response->assertStatus(200);
        $response->assertSee('Belum ada data anak yatim');
    }

    /**
     * Test index view shows pagination when more than 10 records.
     */
    public function test_index_view_shows_pagination(): void
    {
        AnakYatim::factory()->count(15)->create();

        $response = $this->get(route('anak-yatim.index'));

        $response->assertStatus(200);
        $response->assertSee('Menampilkan');
        $response->assertSee('dari');
        $response->assertSee('data');
    }

    /**
     * Test index view shows active filters.
     */
    public function test_index_view_shows_active_filters(): void
    {
        AnakYatim::factory()->count(5)->create();

        $response = $this->get(route('anak-yatim.index', [
            'search' => 'Ahmad',
            'pendidikan' => 'SD',
            'tahun_masuk' => '2020'
        ]));

        $response->assertStatus(200);
        $response->assertSee('Filter aktif:');
        $response->assertSee('Pencarian: Ahmad');
        $response->assertSee('Pendidikan: SD');
        $response->assertSee('Tahun Masuk: 2020');
    }

    /**
     * Test delete confirmation JavaScript function exists.
     */
    public function test_delete_confirmation_script_exists(): void
    {
        AnakYatim::factory()->create();

        $response = $this->get(route('anak-yatim.index'));

        $response->assertStatus(200);
        $response->assertSee('confirmDelete', false);
        $response->assertSee('Apakah Anda yakin ingin menghapus data', false);
    }
}
