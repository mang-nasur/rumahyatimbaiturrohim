<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRoleMethodsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test hasRole() method with admin role
     */
    public function test_has_role_returns_true_for_admin(): void
    {
        $user = User::factory()->admin()->create();
        
        $this->assertTrue($user->hasRole('admin'));
        $this->assertFalse($user->hasRole('bendahara'));
        $this->assertFalse($user->hasRole('staff'));
    }

    /**
     * Test hasRole() method with bendahara role
     */
    public function test_has_role_returns_true_for_bendahara(): void
    {
        $user = User::factory()->bendahara()->create();
        
        $this->assertTrue($user->hasRole('bendahara'));
        $this->assertFalse($user->hasRole('admin'));
        $this->assertFalse($user->hasRole('staff'));
    }

    /**
     * Test hasRole() method with staff role
     */
    public function test_has_role_returns_true_for_staff(): void
    {
        $user = User::factory()->staff()->create();
        
        $this->assertTrue($user->hasRole('staff'));
        $this->assertFalse($user->hasRole('admin'));
        $this->assertFalse($user->hasRole('bendahara'));
    }

    /**
     * Test isAdmin() method
     */
    public function test_is_admin_returns_true_only_for_admin(): void
    {
        $admin = User::factory()->admin()->create();
        $bendahara = User::factory()->bendahara()->create();
        $staff = User::factory()->staff()->create();
        
        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($bendahara->isAdmin());
        $this->assertFalse($staff->isAdmin());
    }

    /**
     * Test isBendahara() method
     */
    public function test_is_bendahara_returns_true_only_for_bendahara(): void
    {
        $admin = User::factory()->admin()->create();
        $bendahara = User::factory()->bendahara()->create();
        $staff = User::factory()->staff()->create();
        
        $this->assertFalse($admin->isBendahara());
        $this->assertTrue($bendahara->isBendahara());
        $this->assertFalse($staff->isBendahara());
    }

    /**
     * Test isStaff() method
     */
    public function test_is_staff_returns_true_only_for_staff(): void
    {
        $admin = User::factory()->admin()->create();
        $bendahara = User::factory()->bendahara()->create();
        $staff = User::factory()->staff()->create();
        
        $this->assertFalse($admin->isStaff());
        $this->assertFalse($bendahara->isStaff());
        $this->assertTrue($staff->isStaff());
    }

    /**
     * Test canAccess() method for admin - should have universal access
     */
    public function test_admin_can_access_all_features(): void
    {
        $admin = User::factory()->admin()->create();
        
        // Admin should have access to all features
        $this->assertTrue($admin->canAccess('transaksi.create'));
        $this->assertTrue($admin->canAccess('transaksi.read'));
        $this->assertTrue($admin->canAccess('transaksi.update'));
        $this->assertTrue($admin->canAccess('transaksi.delete'));
        $this->assertTrue($admin->canAccess('anak-yatim.create'));
        $this->assertTrue($admin->canAccess('anak-yatim.read'));
        $this->assertTrue($admin->canAccess('anak-yatim.update'));
        $this->assertTrue($admin->canAccess('anak-yatim.delete'));
        $this->assertTrue($admin->canAccess('laporan.read'));
        $this->assertTrue($admin->canAccess('laporan.export'));
        $this->assertTrue($admin->canAccess('dashboard.read'));
        $this->assertTrue($admin->canAccess('user.management'));
        $this->assertTrue($admin->canAccess('any.feature'));
    }

    /**
     * Test canAccess() method for bendahara
     */
    public function test_bendahara_has_correct_permissions(): void
    {
        $bendahara = User::factory()->bendahara()->create();
        
        // Bendahara should have full CRUD on transaksi
        $this->assertTrue($bendahara->canAccess('transaksi.create'));
        $this->assertTrue($bendahara->canAccess('transaksi.read'));
        $this->assertTrue($bendahara->canAccess('transaksi.update'));
        $this->assertTrue($bendahara->canAccess('transaksi.delete'));
        
        // Bendahara should have read-only on anak yatim
        $this->assertTrue($bendahara->canAccess('anak-yatim.read'));
        $this->assertFalse($bendahara->canAccess('anak-yatim.create'));
        $this->assertFalse($bendahara->canAccess('anak-yatim.update'));
        $this->assertFalse($bendahara->canAccess('anak-yatim.delete'));
        
        // Bendahara should have access to laporan
        $this->assertTrue($bendahara->canAccess('laporan.read'));
        $this->assertTrue($bendahara->canAccess('laporan.export'));
        
        // Bendahara should have access to dashboard
        $this->assertTrue($bendahara->canAccess('dashboard.read'));
        
        // Bendahara should NOT have access to user management
        $this->assertFalse($bendahara->canAccess('user.management'));
    }

    /**
     * Test canAccess() method for staff
     */
    public function test_staff_has_correct_permissions(): void
    {
        $staff = User::factory()->staff()->create();
        
        // Staff should have full CRUD on anak yatim
        $this->assertTrue($staff->canAccess('anak-yatim.create'));
        $this->assertTrue($staff->canAccess('anak-yatim.read'));
        $this->assertTrue($staff->canAccess('anak-yatim.update'));
        $this->assertTrue($staff->canAccess('anak-yatim.delete'));
        
        // Staff should NOT have access to transaksi CRUD
        $this->assertFalse($staff->canAccess('transaksi.create'));
        $this->assertFalse($staff->canAccess('transaksi.read'));
        $this->assertFalse($staff->canAccess('transaksi.update'));
        $this->assertFalse($staff->canAccess('transaksi.delete'));
        
        // Staff should have read-only access to laporan
        $this->assertTrue($staff->canAccess('laporan.read'));
        $this->assertTrue($staff->canAccess('laporan.export'));
        
        // Staff should have access to dashboard
        $this->assertTrue($staff->canAccess('dashboard.read'));
        
        // Staff should NOT have access to user management
        $this->assertFalse($staff->canAccess('user.management'));
    }

    /**
     * Test canAccess() with invalid feature returns false
     */
    public function test_can_access_returns_false_for_invalid_feature(): void
    {
        $bendahara = User::factory()->bendahara()->create();
        $staff = User::factory()->staff()->create();
        
        $this->assertFalse($bendahara->canAccess('invalid.feature'));
        $this->assertFalse($staff->canAccess('nonexistent.permission'));
    }

    /**
     * Test role methods with various role combinations
     */
    public function test_role_methods_with_multiple_users(): void
    {
        $users = [
            User::factory()->admin()->create(),
            User::factory()->bendahara()->create(),
            User::factory()->staff()->create(),
        ];
        
        // Test that each user has exactly one role
        foreach ($users as $user) {
            $roleCount = 0;
            if ($user->isAdmin()) $roleCount++;
            if ($user->isBendahara()) $roleCount++;
            if ($user->isStaff()) $roleCount++;
            
            $this->assertEquals(1, $roleCount, 'Each user should have exactly one role');
        }
    }
}
