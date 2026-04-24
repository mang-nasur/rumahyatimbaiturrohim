<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\AnakYatim;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AnakYatimControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test index method displays paginated list.
     */
    public function test_index_displays_paginated_list(): void
    {
        $user = User::factory()->create(['role' => 'staff']);
        
        AnakYatim::factory()->count(15)->create();

        $response = $this->actingAs($user)->get(route('anak-yatim.index'));

        $response->assertStatus(200);
        $response->assertViewIs('anak-yatim.index');
        $response->assertViewHas('anakYatim');
    }

    /**
     * Test index method with search parameter.
     */
    public function test_index_with_search_filters_results(): void
    {
        $user = User::factory()->create(['role' => 'staff']);
        
        AnakYatim::factory()->create(['nama_lengkap' => 'Ahmad Fauzi']);
        AnakYatim::factory()->create(['nama_lengkap' => 'Siti Aminah']);

        $response = $this->actingAs($user)->get(route('anak-yatim.index', ['search' => 'Ahmad']));

        $response->assertStatus(200);
        $response->assertSee('Ahmad Fauzi');
        $response->assertDontSee('Siti Aminah');
    }

    /**
     * Test create method displays form.
     */
    public function test_create_displays_form(): void
    {
        $user = User::factory()->create(['role' => 'staff']);
        
        $response = $this->actingAs($user)->get(route('anak-yatim.create'));

        $response->assertStatus(200);
        $response->assertViewIs('anak-yatim.create');
    }

    /**
     * Test store method creates new record.
     */
    public function test_store_creates_new_record(): void
    {
        $user = User::factory()->create(['role' => 'staff']);
        
        Storage::fake('public');

        $data = [
            'nama_lengkap' => 'Ahmad Fauzi',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2010-05-15',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_masuk' => '2020-01-10',
        ];

        $response = $this->actingAs($user)->post(route('anak-yatim.store'), $data);

        $response->assertRedirect(route('anak-yatim.index'));
        $response->assertSessionHas('success', 'Data anak yatim berhasil ditambahkan.');
        $this->assertDatabaseHas('anak_yatim', [
            'nama_lengkap' => 'Ahmad Fauzi',
            'tempat_lahir' => 'Jakarta',
        ]);
    }

    /**
     * Test store method with photo upload.
     */
    public function test_store_with_photo_upload(): void
    {
        $user = User::factory()->create(['role' => 'staff']);
        
        Storage::fake('public');

        $photo = UploadedFile::fake()->create('test.jpg', 100, 'image/jpeg');

        $data = [
            'nama_lengkap' => 'Ahmad Fauzi',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2010-05-15',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_masuk' => '2020-01-10',
            'foto' => $photo,
        ];

        $response = $this->actingAs($user)->post(route('anak-yatim.store'), $data);

        $response->assertRedirect(route('anak-yatim.index'));
        
        $anakYatim = AnakYatim::where('nama_lengkap', 'Ahmad Fauzi')->first();
        $this->assertNotNull($anakYatim->foto);
        Storage::disk('public')->assertExists($anakYatim->foto);
    }

    /**
     * Test show method displays detail.
     */
    public function test_show_displays_detail(): void
    {
        $user = User::factory()->create(['role' => 'staff']);
        
        $anakYatim = AnakYatim::factory()->create();

        $response = $this->actingAs($user)->get(route('anak-yatim.show', $anakYatim));

        $response->assertStatus(200);
        $response->assertViewIs('anak-yatim.show');
        $response->assertViewHas('anakYatim', $anakYatim);
    }

    /**
     * Test edit method displays form with existing data.
     */
    public function test_edit_displays_form_with_data(): void
    {
        $user = User::factory()->create(['role' => 'staff']);
        
        $anakYatim = AnakYatim::factory()->create();

        $response = $this->actingAs($user)->get(route('anak-yatim.edit', $anakYatim));

        $response->assertStatus(200);
        $response->assertViewIs('anak-yatim.edit');
        $response->assertViewHas('anakYatim', $anakYatim);
    }

    /**
     * Test update method updates existing record.
     */
    public function test_update_updates_existing_record(): void
    {
        $user = User::factory()->create(['role' => 'staff']);
        
        $anakYatim = AnakYatim::factory()->create([
            'nama_lengkap' => 'Old Name',
        ]);

        $data = [
            'nama_lengkap' => 'New Name',
            'tempat_lahir' => $anakYatim->tempat_lahir,
            'tanggal_lahir' => $anakYatim->tanggal_lahir->format('Y-m-d'),
            'jenis_kelamin' => $anakYatim->jenis_kelamin,
            'tanggal_masuk' => $anakYatim->tanggal_masuk->format('Y-m-d'),
        ];

        $response = $this->actingAs($user)->put(route('anak-yatim.update', $anakYatim), $data);

        $response->assertRedirect(route('anak-yatim.index'));
        $response->assertSessionHas('success', 'Data anak yatim berhasil diperbarui.');
        $this->assertDatabaseHas('anak_yatim', [
            'id' => $anakYatim->id,
            'nama_lengkap' => 'New Name',
        ]);
    }

    /**
     * Test update method replaces old photo.
     */
    public function test_update_replaces_old_photo(): void
    {
        $user = User::factory()->create(['role' => 'staff']);
        
        Storage::fake('public');

        $oldPhoto = UploadedFile::fake()->create('old.jpg', 100, 'image/jpeg');
        $oldPhotoPath = $oldPhoto->storeAs('photos', 'old_photo.jpg', 'public');

        $anakYatim = AnakYatim::factory()->create([
            'foto' => $oldPhotoPath,
        ]);

        $newPhoto = UploadedFile::fake()->create('new.jpg', 100, 'image/jpeg');

        $data = [
            'nama_lengkap' => $anakYatim->nama_lengkap,
            'tempat_lahir' => $anakYatim->tempat_lahir,
            'tanggal_lahir' => $anakYatim->tanggal_lahir->format('Y-m-d'),
            'jenis_kelamin' => $anakYatim->jenis_kelamin,
            'tanggal_masuk' => $anakYatim->tanggal_masuk->format('Y-m-d'),
            'foto' => $newPhoto,
        ];

        $response = $this->actingAs($user)->put(route('anak-yatim.update', $anakYatim), $data);

        $response->assertRedirect(route('anak-yatim.index'));
        
        $anakYatim->refresh();
        $this->assertNotEquals($oldPhotoPath, $anakYatim->foto);
        Storage::disk('public')->assertMissing($oldPhotoPath);
        Storage::disk('public')->assertExists($anakYatim->foto);
    }

    /**
     * Test destroy method deletes record and photo.
     */
    public function test_destroy_deletes_record_and_photo(): void
    {
        $user = User::factory()->create(['role' => 'staff']);
        
        Storage::fake('public');

        $photo = UploadedFile::fake()->create('test.jpg', 100, 'image/jpeg');
        $photoPath = $photo->storeAs('photos', 'test_photo.jpg', 'public');

        $anakYatim = AnakYatim::factory()->create([
            'foto' => $photoPath,
        ]);

        $response = $this->actingAs($user)->delete(route('anak-yatim.destroy', $anakYatim));

        $response->assertRedirect(route('anak-yatim.index'));
        $response->assertSessionHas('success', 'Data anak yatim berhasil dihapus.');
        $this->assertDatabaseMissing('anak_yatim', [
            'id' => $anakYatim->id,
        ]);
        Storage::disk('public')->assertMissing($photoPath);
    }

    /**
     * Test admin can access all anak yatim features.
     */
    public function test_admin_can_access_all_features(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $anakYatim = AnakYatim::factory()->create();

        // Test create
        $response = $this->actingAs($admin)->get(route('anak-yatim.create'));
        $response->assertStatus(200);

        // Test store
        $data = [
            'nama_lengkap' => 'Test Admin Create',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2010-05-15',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_masuk' => '2020-01-10',
        ];
        $response = $this->actingAs($admin)->post(route('anak-yatim.store'), $data);
        $response->assertRedirect(route('anak-yatim.index'));

        // Test edit
        $response = $this->actingAs($admin)->get(route('anak-yatim.edit', $anakYatim));
        $response->assertStatus(200);

        // Test update
        $data = [
            'nama_lengkap' => 'Updated by Admin',
            'tempat_lahir' => $anakYatim->tempat_lahir,
            'tanggal_lahir' => $anakYatim->tanggal_lahir->format('Y-m-d'),
            'jenis_kelamin' => $anakYatim->jenis_kelamin,
            'tanggal_masuk' => $anakYatim->tanggal_masuk->format('Y-m-d'),
        ];
        $response = $this->actingAs($admin)->put(route('anak-yatim.update', $anakYatim), $data);
        $response->assertRedirect(route('anak-yatim.index'));

        // Test destroy
        $response = $this->actingAs($admin)->delete(route('anak-yatim.destroy', $anakYatim));
        $response->assertRedirect(route('anak-yatim.index'));
    }

    /**
     * Test staff can access all anak yatim CRUD operations.
     */
    public function test_staff_can_access_all_crud_operations(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $anakYatim = AnakYatim::factory()->create();

        // Test create
        $response = $this->actingAs($staff)->get(route('anak-yatim.create'));
        $response->assertStatus(200);

        // Test store
        $data = [
            'nama_lengkap' => 'Test Staff Create',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2010-05-15',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_masuk' => '2020-01-10',
        ];
        $response = $this->actingAs($staff)->post(route('anak-yatim.store'), $data);
        $response->assertRedirect(route('anak-yatim.index'));

        // Test edit
        $response = $this->actingAs($staff)->get(route('anak-yatim.edit', $anakYatim));
        $response->assertStatus(200);

        // Test update
        $data = [
            'nama_lengkap' => 'Updated by Staff',
            'tempat_lahir' => $anakYatim->tempat_lahir,
            'tanggal_lahir' => $anakYatim->tanggal_lahir->format('Y-m-d'),
            'jenis_kelamin' => $anakYatim->jenis_kelamin,
            'tanggal_masuk' => $anakYatim->tanggal_masuk->format('Y-m-d'),
        ];
        $response = $this->actingAs($staff)->put(route('anak-yatim.update', $anakYatim), $data);
        $response->assertRedirect(route('anak-yatim.index'));

        // Test destroy
        $response = $this->actingAs($staff)->delete(route('anak-yatim.destroy', $anakYatim));
        $response->assertRedirect(route('anak-yatim.index'));
    }

    /**
     * Test bendahara has read-only access to anak yatim.
     */
    public function test_bendahara_has_read_only_access(): void
    {
        $bendahara = User::factory()->create(['role' => 'bendahara']);
        $anakYatim = AnakYatim::factory()->create();

        // Test index (read) - should work
        $response = $this->actingAs($bendahara)->get(route('anak-yatim.index'));
        $response->assertStatus(200);

        // Test show (read) - should work
        $response = $this->actingAs($bendahara)->get(route('anak-yatim.show', $anakYatim));
        $response->assertStatus(200);

        // Test create - should be forbidden
        $response = $this->actingAs($bendahara)->get(route('anak-yatim.create'));
        $response->assertStatus(403);

        // Test store - should be forbidden
        $data = [
            'nama_lengkap' => 'Test Bendahara Create',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2010-05-15',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_masuk' => '2020-01-10',
        ];
        $response = $this->actingAs($bendahara)->post(route('anak-yatim.store'), $data);
        $response->assertStatus(403);

        // Test edit - should be forbidden
        $response = $this->actingAs($bendahara)->get(route('anak-yatim.edit', $anakYatim));
        $response->assertStatus(403);

        // Test update - should be forbidden
        $data = [
            'nama_lengkap' => 'Updated by Bendahara',
            'tempat_lahir' => $anakYatim->tempat_lahir,
            'tanggal_lahir' => $anakYatim->tanggal_lahir->format('Y-m-d'),
            'jenis_kelamin' => $anakYatim->jenis_kelamin,
            'tanggal_masuk' => $anakYatim->tanggal_masuk->format('Y-m-d'),
        ];
        $response = $this->actingAs($bendahara)->put(route('anak-yatim.update', $anakYatim), $data);
        $response->assertStatus(403);

        // Test destroy - should be forbidden
        $response = $this->actingAs($bendahara)->delete(route('anak-yatim.destroy', $anakYatim));
        $response->assertStatus(403);
    }
}
