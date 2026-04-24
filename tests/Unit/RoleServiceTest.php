<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\RoleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleServiceTest extends TestCase
{
    use RefreshDatabase;

    protected RoleService $roleService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->roleService = new RoleService();
    }

    /**
     * Test getAvailableRoles returns correct roles
     * 
     * Requirements: 2.1
     */
    public function test_get_available_roles_returns_correct_roles(): void
    {
        $roles = $this->roleService->getAvailableRoles();

        $this->assertIsArray($roles);
        $this->assertCount(3, $roles);
        $this->assertContains('admin', $roles);
        $this->assertContains('bendahara', $roles);
        $this->assertContains('staff', $roles);
    }

    /**
     * Test admin has universal access to all features
     * 
     * Requirements: 2.2
     */
    public function test_admin_has_universal_access(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $features = ['anak-yatim', 'transaksi', 'laporan', 'user-management', 'dashboard'];

        foreach ($features as $feature) {
            $this->assertTrue(
                $this->roleService->canAccessFeature($admin, $feature),
                "Admin should have access to {$feature}"
            );
        }
    }

    /**
     * Test bendahara permissions match the permission matrix
     * 
     * Requirements: 2.3, 2.4
     */
    public function test_bendahara_permissions(): void
    {
        $bendahara = User::factory()->create(['role' => 'bendahara']);

        // Bendahara should have access to these features
        $this->assertTrue($this->roleService->canAccessFeature($bendahara, 'anak-yatim'));
        $this->assertTrue($this->roleService->canAccessFeature($bendahara, 'transaksi'));
        $this->assertTrue($this->roleService->canAccessFeature($bendahara, 'laporan'));
        $this->assertTrue($this->roleService->canAccessFeature($bendahara, 'dashboard'));

        // Bendahara should NOT have access to user management
        $this->assertFalse($this->roleService->canAccessFeature($bendahara, 'user-management'));
    }

    /**
     * Test staff permissions match the permission matrix
     * 
     * Requirements: 2.5, 2.6, 2.7
     */
    public function test_staff_permissions(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);

        // Staff should have access to these features
        $this->assertTrue($this->roleService->canAccessFeature($staff, 'anak-yatim'));
        $this->assertTrue($this->roleService->canAccessFeature($staff, 'laporan'));
        $this->assertTrue($this->roleService->canAccessFeature($staff, 'dashboard'));

        // Staff should NOT have access to transaksi or user management
        $this->assertFalse($this->roleService->canAccessFeature($staff, 'transaksi'));
        $this->assertFalse($this->roleService->canAccessFeature($staff, 'user-management'));
    }

    /**
     * Test getRolePermissions returns correct permissions for admin
     * 
     * Requirements: 2.2
     */
    public function test_get_role_permissions_for_admin(): void
    {
        $permissions = $this->roleService->getRolePermissions('admin');

        $this->assertIsArray($permissions);
        $this->assertArrayHasKey('anak-yatim', $permissions);
        $this->assertArrayHasKey('transaksi', $permissions);
        $this->assertArrayHasKey('laporan', $permissions);
        $this->assertArrayHasKey('user-management', $permissions);
        $this->assertArrayHasKey('dashboard', $permissions);

        // Admin should have all CRUD permissions
        $this->assertEquals(['create', 'read', 'update', 'delete'], $permissions['anak-yatim']);
        $this->assertEquals(['create', 'read', 'update', 'delete'], $permissions['transaksi']);
    }

    /**
     * Test getRolePermissions returns correct permissions for bendahara
     * 
     * Requirements: 2.3, 2.4
     */
    public function test_get_role_permissions_for_bendahara(): void
    {
        $permissions = $this->roleService->getRolePermissions('bendahara');

        $this->assertIsArray($permissions);
        
        // Bendahara has full CRUD on transaksi
        $this->assertArrayHasKey('transaksi', $permissions);
        $this->assertEquals(['create', 'read', 'update', 'delete'], $permissions['transaksi']);

        // Bendahara has read-only on anak-yatim
        $this->assertArrayHasKey('anak-yatim', $permissions);
        $this->assertEquals(['read'], $permissions['anak-yatim']);

        // Bendahara has read and export on laporan
        $this->assertArrayHasKey('laporan', $permissions);
        $this->assertEquals(['read', 'export'], $permissions['laporan']);

        // Bendahara should NOT have user-management
        $this->assertArrayNotHasKey('user-management', $permissions);
    }

    /**
     * Test getRolePermissions returns correct permissions for staff
     * 
     * Requirements: 2.5, 2.6, 2.7
     */
    public function test_get_role_permissions_for_staff(): void
    {
        $permissions = $this->roleService->getRolePermissions('staff');

        $this->assertIsArray($permissions);
        
        // Staff has full CRUD on anak-yatim
        $this->assertArrayHasKey('anak-yatim', $permissions);
        $this->assertEquals(['create', 'read', 'update', 'delete'], $permissions['anak-yatim']);

        // Staff has read and export on laporan
        $this->assertArrayHasKey('laporan', $permissions);
        $this->assertEquals(['read', 'export'], $permissions['laporan']);

        // Staff should NOT have transaksi or user-management
        $this->assertArrayNotHasKey('transaksi', $permissions);
        $this->assertArrayNotHasKey('user-management', $permissions);
    }

    /**
     * Test getRolePermissions returns empty array for invalid role
     */
    public function test_get_role_permissions_for_invalid_role(): void
    {
        $permissions = $this->roleService->getRolePermissions('invalid-role');

        $this->assertIsArray($permissions);
        $this->assertEmpty($permissions);
    }

    /**
     * Test canPerformAction for admin on all features
     * 
     * Requirements: 2.2
     */
    public function test_admin_can_perform_all_actions(): void
    {
        $actions = ['create', 'read', 'update', 'delete'];
        $features = ['anak-yatim', 'transaksi', 'laporan', 'user-management'];

        foreach ($features as $feature) {
            foreach ($actions as $action) {
                $this->assertTrue(
                    $this->roleService->canPerformAction('admin', $feature, $action),
                    "Admin should be able to {$action} on {$feature}"
                );
            }
        }
    }

    /**
     * Test canPerformAction for bendahara on transaksi (full CRUD)
     * 
     * Requirements: 2.3
     */
    public function test_bendahara_can_perform_crud_on_transaksi(): void
    {
        $actions = ['create', 'read', 'update', 'delete'];

        foreach ($actions as $action) {
            $this->assertTrue(
                $this->roleService->canPerformAction('bendahara', 'transaksi', $action),
                "Bendahara should be able to {$action} on transaksi"
            );
        }
    }

    /**
     * Test canPerformAction for bendahara on anak-yatim (read-only)
     * 
     * Requirements: 2.4
     */
    public function test_bendahara_can_only_read_anak_yatim(): void
    {
        // Bendahara can read
        $this->assertTrue($this->roleService->canPerformAction('bendahara', 'anak-yatim', 'read'));

        // Bendahara cannot create, update, or delete
        $this->assertFalse($this->roleService->canPerformAction('bendahara', 'anak-yatim', 'create'));
        $this->assertFalse($this->roleService->canPerformAction('bendahara', 'anak-yatim', 'update'));
        $this->assertFalse($this->roleService->canPerformAction('bendahara', 'anak-yatim', 'delete'));
    }

    /**
     * Test canPerformAction for staff on anak-yatim (full CRUD)
     * 
     * Requirements: 2.5
     */
    public function test_staff_can_perform_crud_on_anak_yatim(): void
    {
        $actions = ['create', 'read', 'update', 'delete'];

        foreach ($actions as $action) {
            $this->assertTrue(
                $this->roleService->canPerformAction('staff', 'anak-yatim', $action),
                "Staff should be able to {$action} on anak-yatim"
            );
        }
    }

    /**
     * Test canPerformAction for staff on laporan (read-only)
     * 
     * Requirements: 2.6
     */
    public function test_staff_can_read_and_export_laporan(): void
    {
        // Staff can read and export
        $this->assertTrue($this->roleService->canPerformAction('staff', 'laporan', 'read'));
        $this->assertTrue($this->roleService->canPerformAction('staff', 'laporan', 'export'));

        // Staff cannot create, update, or delete laporan
        $this->assertFalse($this->roleService->canPerformAction('staff', 'laporan', 'create'));
        $this->assertFalse($this->roleService->canPerformAction('staff', 'laporan', 'update'));
        $this->assertFalse($this->roleService->canPerformAction('staff', 'laporan', 'delete'));
    }

    /**
     * Test canPerformAction for staff on transaksi (no access)
     * 
     * Requirements: 2.7
     */
    public function test_staff_cannot_access_transaksi(): void
    {
        $actions = ['create', 'read', 'update', 'delete'];

        foreach ($actions as $action) {
            $this->assertFalse(
                $this->roleService->canPerformAction('staff', 'transaksi', $action),
                "Staff should NOT be able to {$action} on transaksi"
            );
        }
    }

    /**
     * Test canPerformAction returns false for invalid role
     */
    public function test_can_perform_action_returns_false_for_invalid_role(): void
    {
        $this->assertFalse($this->roleService->canPerformAction('invalid-role', 'anak-yatim', 'read'));
    }

    /**
     * Test canPerformAction returns false for invalid feature
     */
    public function test_can_perform_action_returns_false_for_invalid_feature(): void
    {
        $this->assertFalse($this->roleService->canPerformAction('staff', 'invalid-feature', 'read'));
    }

    /**
     * Test permission matrix completeness for all roles
     * 
     * Requirements: 2.2, 2.3, 2.4, 2.5, 2.6, 2.7
     */
    public function test_permission_matrix_completeness(): void
    {
        $roles = ['admin', 'bendahara', 'staff'];

        foreach ($roles as $role) {
            $permissions = $this->roleService->getRolePermissions($role);
            $this->assertIsArray($permissions, "Permissions for {$role} should be an array");
            $this->assertNotEmpty($permissions, "Permissions for {$role} should not be empty");
        }
    }
}
