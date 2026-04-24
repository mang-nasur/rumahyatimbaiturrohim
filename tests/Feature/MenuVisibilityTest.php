<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MenuVisibilityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Property 28: Menu visibility respects roles
     * 
     * For any authenticated user, the navigation menu should only display 
     * items that the user's role has permission to access.
     * 
     * **Validates: Requirements 8.6**
     */
    public function test_property_menu_visibility_respects_roles(): void
    {
        $roles = ['admin', 'bendahara', 'staff'];
        
        foreach ($roles as $role) {
            // Generate multiple users for each role to test property
            for ($i = 0; $i < 10; $i++) {
                $user = User::factory()->create(['role' => $role]);
                
                $response = $this->actingAs($user)->get('/');
                
                $response->assertStatus(200);
                
                // All authenticated users should see Dashboard
                $response->assertSee('Dashboard');
                
                // All authenticated users should see Laporan
                $response->assertSee('Laporan');
                
                // All authenticated users should see their profile menu
                $response->assertSee($user->name);
                $response->assertSee('Profil Saya');
                $response->assertSee('Edit Profil');
                $response->assertSee('Ubah Password');
                $response->assertSee('Logout');
                
                // Role-specific menu visibility - check for navigation menu items
                $content = $response->getContent();
                
                // Extract navigation menu section only
                preg_match('/<nav class="navbar.*?<\/nav>/s', $content, $navMatches);
                $navContent = $navMatches[0] ?? '';
                
                if ($role === 'admin') {
                    // Admin should see all menu items in navigation
                    $this->assertStringContainsString('Data Anak Yatim', $navContent);
                    $this->assertStringContainsString('id="keuanganDropdown"', $navContent);
                    $this->assertStringContainsString('Manajemen User', $navContent);
                } elseif ($role === 'bendahara') {
                    // Bendahara should see Keuangan but not Data Anak Yatim or Manajemen User in nav
                    $this->assertStringContainsString('id="keuanganDropdown"', $navContent);
                    $this->assertStringNotContainsString('Data Anak Yatim', $navContent);
                    $this->assertStringNotContainsString('Manajemen User', $navContent);
                } elseif ($role === 'staff') {
                    // Staff should see Data Anak Yatim but not Keuangan or Manajemen User in nav
                    $this->assertStringContainsString('Data Anak Yatim', $navContent);
                    $this->assertStringNotContainsString('id="keuanganDropdown"', $navContent);
                    $this->assertStringNotContainsString('Manajemen User', $navContent);
                }
            }
        }
    }

    /**
     * Test admin sees all menu items
     */
    public function test_admin_sees_all_menu_items(): void
    {
        $admin = User::factory()->admin()->create();
        
        $response = $this->actingAs($admin)->get('/');
        
        $response->assertStatus(200);
        
        $content = $response->getContent();
        preg_match('/<nav class="navbar.*?<\/nav>/s', $content, $navMatches);
        $navContent = $navMatches[0] ?? '';
        
        $this->assertStringContainsString('Dashboard', $navContent);
        $this->assertStringContainsString('Data Anak Yatim', $navContent);
        $this->assertStringContainsString('Keuangan', $navContent);
        $this->assertStringContainsString('Laporan', $navContent);
        $this->assertStringContainsString('Manajemen User', $navContent);
        $this->assertStringContainsString($admin->name, $navContent);
    }

    /**
     * Test bendahara sees appropriate menu items
     */
    public function test_bendahara_sees_appropriate_menu_items(): void
    {
        $bendahara = User::factory()->bendahara()->create();
        
        $response = $this->actingAs($bendahara)->get('/');
        
        $response->assertStatus(200);
        
        $content = $response->getContent();
        preg_match('/<nav class="navbar.*?<\/nav>/s', $content, $navMatches);
        $navContent = $navMatches[0] ?? '';
        
        $this->assertStringContainsString('Dashboard', $navContent);
        $this->assertStringContainsString('Keuangan', $navContent);
        $this->assertStringContainsString('Laporan', $navContent);
        $this->assertStringContainsString($bendahara->name, $navContent);
        
        // Should not see these items in navigation
        $this->assertStringNotContainsString('Data Anak Yatim', $navContent);
        $this->assertStringNotContainsString('Manajemen User', $navContent);
    }

    /**
     * Test staff sees appropriate menu items
     */
    public function test_staff_sees_appropriate_menu_items(): void
    {
        $staff = User::factory()->staff()->create();
        
        $response = $this->actingAs($staff)->get('/');
        
        $response->assertStatus(200);
        
        $content = $response->getContent();
        preg_match('/<nav class="navbar.*?<\/nav>/s', $content, $navMatches);
        $navContent = $navMatches[0] ?? '';
        
        $this->assertStringContainsString('Dashboard', $navContent);
        $this->assertStringContainsString('Data Anak Yatim', $navContent);
        $this->assertStringContainsString('Laporan', $navContent);
        $this->assertStringContainsString($staff->name, $navContent);
        
        // Should not see these items in navigation
        $this->assertStringNotContainsString('id="keuanganDropdown"', $navContent);
        $this->assertStringNotContainsString('Manajemen User', $navContent);
    }

    /**
     * Test unauthenticated user doesn't see navigation menu
     */
    public function test_unauthenticated_user_sees_no_menu(): void
    {
        $response = $this->get('/');
        
        // Should redirect to login
        $response->assertRedirect('/login');
    }

    /**
     * Test profile dropdown contains correct items
     */
    public function test_profile_dropdown_contains_correct_items(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/');
        
        $response->assertStatus(200);
        $response->assertSee('Profil Saya');
        $response->assertSee('Edit Profil');
        $response->assertSee('Ubah Password');
        $response->assertSee('Logout');
    }

    /**
     * Test keuangan dropdown contains correct items for authorized users
     */
    public function test_keuangan_dropdown_contains_correct_items(): void
    {
        $admin = User::factory()->admin()->create();
        
        $response = $this->actingAs($admin)->get('/');
        
        $response->assertStatus(200);
        $response->assertSee('Dashboard Keuangan');
        $response->assertSee('Transaksi');
        $response->assertSee('Tambah Transaksi');
    }
}
