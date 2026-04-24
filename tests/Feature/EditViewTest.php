<?php

namespace Tests\Feature;

use App\Models\AnakYatim;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_edit_view_form_is_prefilled_with_existing_data(): void
    {
        $anak = AnakYatim::factory()->create([
            'nama_lengkap' => 'Ahmad Test',
            'jenis_kelamin' => 'Laki-laki',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2010-05-15',
            'alamat' => 'Jl. Test No. 123',
            'nama_ayah' => 'Budi Test',
            'status_ayah' => 'Meninggal',
            'pendidikan_terakhir' => 'SD',
            'tanggal_masuk' => '2020-01-10',
        ]);

        $response = $this->get(route('anak-yatim.edit', $anak));

        $response->assertStatus(200);
        $response->assertSee('Edit Data Anak Yatim');
        $response->assertSee('value="Ahmad Test"', false);
        $response->assertSee('value="Jakarta"', false);
        $response->assertSee('value="2010-05-15"', false);
        $response->assertSee('Jl. Test No. 123');
        $response->assertSee('value="Budi Test"', false);
        $response->assertSee('value="2020-01-10"', false);
    }

    public function test_edit_view_displays_existing_photo_if_present(): void
    {
        $anak = AnakYatim::factory()->create([
            'foto' => 'photos/test-photo.jpg',
        ]);

        $response = $this->get(route('anak-yatim.edit', $anak));

        $response->assertStatus(200);
        $response->assertSee('Foto Saat Ini');
        $response->assertSee('storage/photos/test-photo.jpg', false);
    }

    public function test_edit_view_has_optional_file_input_for_new_photo(): void
    {
        $anak = AnakYatim::factory()->create();

        $response = $this->get(route('anak-yatim.edit', $anak));

        $response->assertStatus(200);
        $response->assertSee('Upload Foto Baru (Opsional)');
        $response->assertSee('type="file"', false);
        $response->assertSee('name="foto"', false);
    }

    public function test_edit_view_displays_validation_errors_inline(): void
    {
        $anak = AnakYatim::factory()->create();

        $response = $this->put(route('anak-yatim.update', $anak), [
            'nama_lengkap' => '', // Invalid: required field
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2010-05-15',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_masuk' => '2020-01-10',
        ]);

        $response->assertSessionHasErrors('nama_lengkap');
        
        // Follow redirect and check error display
        $response = $this->get(route('anak-yatim.edit', $anak));
        $response->assertSee('is-invalid');
    }

    public function test_edit_view_has_back_and_save_buttons(): void
    {
        $anak = AnakYatim::factory()->create();

        $response = $this->get(route('anak-yatim.edit', $anak));

        $response->assertStatus(200);
        $response->assertSee('Kembali');
        $response->assertSee('Simpan Perubahan');
        $response->assertSee(route('anak-yatim.show', $anak), false);
    }

    public function test_edit_view_form_uses_put_method(): void
    {
        $anak = AnakYatim::factory()->create();

        $response = $this->get(route('anak-yatim.edit', $anak));

        $response->assertStatus(200);
        $response->assertSee('method="POST"', false);
        $response->assertSee('name="_method"', false);
        $response->assertSee('value="PUT"', false);
    }

    public function test_edit_view_includes_csrf_token(): void
    {
        $anak = AnakYatim::factory()->create();

        $response = $this->get(route('anak-yatim.edit', $anak));

        $response->assertStatus(200);
        $response->assertSee('name="_token"', false);
    }

    public function test_edit_view_preserves_selected_dropdown_values(): void
    {
        $anak = AnakYatim::factory()->create([
            'jenis_kelamin' => 'Perempuan',
            'status_ayah' => 'Tidak Diketahui',
            'pendidikan_terakhir' => 'SMP',
        ]);

        $response = $this->get(route('anak-yatim.edit', $anak));

        $response->assertStatus(200);
        $response->assertSee('value="Perempuan" selected', false);
        $response->assertSee('value="Tidak Diketahui" selected', false);
        $response->assertSee('value="SMP" selected', false);
    }
}
