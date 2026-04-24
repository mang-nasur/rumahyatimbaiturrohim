<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateViewTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create_view_can_be_rendered()
    {
        $response = $this->get(route('anak-yatim.create'));

        $response->assertStatus(200);
        $response->assertViewIs('anak-yatim.create');
    }

    /** @test */
    public function create_view_contains_all_required_form_fields()
    {
        $response = $this->get(route('anak-yatim.create'));

        $response->assertStatus(200);
        
        // Check for required fields
        $response->assertSee('nama_lengkap');
        $response->assertSee('tempat_lahir');
        $response->assertSee('tanggal_lahir');
        $response->assertSee('jenis_kelamin');
        $response->assertSee('tanggal_masuk');
        
        // Check for optional fields
        $response->assertSee('alamat');
        $response->assertSee('nama_ayah');
        $response->assertSee('status_ayah');
        $response->assertSee('nama_ibu');
        $response->assertSee('status_ibu');
        $response->assertSee('nomor_telepon_wali');
        $response->assertSee('pendidikan_terakhir');
        $response->assertSee('sekolah_saat_ini');
        $response->assertSee('foto');
    }

    /** @test */
    public function create_view_contains_csrf_token()
    {
        $response = $this->get(route('anak-yatim.create'));

        $response->assertStatus(200);
        $response->assertSee('_token', false);
    }

    /** @test */
    public function create_view_has_correct_form_action()
    {
        $response = $this->get(route('anak-yatim.create'));

        $response->assertStatus(200);
        $response->assertSee(route('anak-yatim.store'), false);
    }

    /** @test */
    public function create_view_displays_validation_errors()
    {
        // Submit empty form to trigger validation errors
        $response = $this->post(route('anak-yatim.store'), []);

        $response->assertSessionHasErrors([
            'nama_lengkap',
            'tempat_lahir',
            'tanggal_lahir',
            'jenis_kelamin',
            'tanggal_masuk'
        ]);
    }

    /** @test */
    public function create_view_preserves_old_input_on_validation_error()
    {
        $data = [
            'nama_lengkap' => 'Test Name',
            'tempat_lahir' => 'Jakarta',
            // Missing required fields to trigger validation error
        ];

        $response = $this->from(route('anak-yatim.create'))
            ->post(route('anak-yatim.store'), $data);

        $response->assertSessionHasErrors();
        $response->assertRedirect(route('anak-yatim.create'));
        
        // Follow redirect and check old input
        $followResponse = $this->get(route('anak-yatim.create'));
        $followResponse->assertSee('value="Test Name"', false);
        $followResponse->assertSee('value="Jakarta"', false);
    }

    /** @test */
    public function create_view_has_photo_preview_functionality()
    {
        $response = $this->get(route('anak-yatim.create'));

        $response->assertStatus(200);
        $response->assertSee('previewImage', false);
        $response->assertSee('photoPreview', false);
    }
}
