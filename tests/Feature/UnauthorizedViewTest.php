<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnauthorizedViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_403_error_view_displays_correctly_for_unauthorized_access()
    {
        // Create a staff user
        $staff = User::factory()->create(['role' => 'staff']);

        // Try to access admin-only user management page
        $response = $this->actingAs($staff)->get(route('users.index'));

        // Should get 403 status
        $response->assertStatus(403);
        
        // Should see the custom error message
        $response->assertSee('Akses Ditolak');
        $response->assertSee('403 - Forbidden');
        $response->assertSee('Anda tidak memiliki akses ke halaman ini');
    }

    public function test_403_error_view_shows_user_info_when_authenticated()
    {
        // Create a bendahara user
        $bendahara = User::factory()->create([
            'name' => 'Test Bendahara',
            'email' => 'bendahara@test.com',
            'role' => 'bendahara'
        ]);

        // Try to access admin-only user management page
        $response = $this->actingAs($bendahara)->get(route('users.index'));

        // Should see user information
        $response->assertSee('Test Bendahara');
        $response->assertSee('bendahara@test.com');
        $response->assertSee('Bendahara');
    }

    public function test_403_error_view_has_navigation_buttons()
    {
        // Create a staff user
        $staff = User::factory()->create(['role' => 'staff']);

        // Try to access admin-only user management page
        $response = $this->actingAs($staff)->get(route('users.index'));

        // Should see navigation buttons
        $response->assertSee('Kembali');
        $response->assertSee('Ke Dashboard');
    }
}
