<?php

namespace Tests\Feature;

use App\Models\AnakYatim;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_view_displays_all_data_in_card_format(): void
    {
        $anak = AnakYatim::factory()->create([
            'nama_lengkap' => 'Ahmad Test',
            'jenis_kelamin' => 'Laki-laki',
            'tempat_lahir' => 'Jakarta',
            'alamat' => 'Jl. Test No. 123',
            'nama_ayah' => 'Budi Test',
            'status_ayah' => 'Meninggal',
            'pendidikan_terakhir' => 'SD',
        ]);

        $response = $this->get(route('anak-yatim.show', $anak));

        $response->assertStatus(200);
        $response->assertSee('Detail Anak Yatim');
        $response->assertSee('Ahmad Test');
        $response->assertSee('Laki-laki');
        $response->assertSee('Jakarta');
        $response->assertSee('Jl. Test No. 123');
        $response->assertSee('Budi Test');
        $response->assertSee('Meninggal');
        $response->assertSee('SD');
    }

    public function test_show_view_displays_photo_when_exists(): void
    {
        $anak = AnakYatim::factory()->create([
            'foto' => 'photos/test-photo.jpg',
        ]);

        $response = $this->get(route('anak-yatim.show', $anak));

        $response->assertStatus(200);
        $response->assertSee('storage/photos/test-photo.jpg', false);
    }

    public function test_show_view_displays_placeholder_when_no_photo(): void
    {
        $anak = AnakYatim::factory()->create([
            'foto' => null,
        ]);

        $response = $this->get(route('anak-yatim.show', $anak));

        $response->assertStatus(200);
        $response->assertSee('Tidak ada foto');
        $response->assertSee('bi-person-circle');
    }

    public function test_show_view_has_back_edit_and_delete_buttons(): void
    {
        $anak = AnakYatim::factory()->create();

        $response = $this->get(route('anak-yatim.show', $anak));

        $response->assertStatus(200);
        $response->assertSee('Kembali');
        $response->assertSee('Edit');
        $response->assertSee('Hapus');
        $response->assertSee(route('anak-yatim.index'), false);
        $response->assertSee(route('anak-yatim.edit', $anak), false);
    }

    public function test_show_view_displays_calculated_age(): void
    {
        $anak = AnakYatim::factory()->create([
            'tanggal_lahir' => now()->subYears(10),
        ]);

        $response = $this->get(route('anak-yatim.show', $anak));

        $response->assertStatus(200);
        $response->assertSee('10 Tahun');
        $response->assertSee('10 tahun');
    }

    public function test_show_view_displays_null_fields_as_dash(): void
    {
        $anak = AnakYatim::factory()->create([
            'alamat' => null,
            'nama_ayah' => null,
            'pendidikan_terakhir' => null,
        ]);

        $response = $this->get(route('anak-yatim.show', $anak));

        $response->assertStatus(200);
        // Check that dashes are displayed for null fields
        $response->assertSee('-');
    }
}
