<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    protected UserService $userService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userService = new UserService();
    }

    /**
     * Test getAllUsers returns paginated users
     * 
     * Requirements: 3.7
     */
    public function test_get_all_users_with_pagination(): void
    {
        // Create 20 users
        User::factory()->count(20)->create();

        $result = $this->userService->getAllUsers(15);

        $this->assertCount(15, $result->items());
        $this->assertEquals(20, $result->total());
        $this->assertEquals(15, $result->perPage());
    }

    /**
     * Test getAllUsers returns users ordered by name
     * 
     * Requirements: 3.7
     */
    public function test_get_all_users_ordered_by_name(): void
    {
        User::factory()->create(['name' => 'Zara']);
        User::factory()->create(['name' => 'Alice']);
        User::factory()->create(['name' => 'Bob']);

        $result = $this->userService->getAllUsers(10);

        $this->assertEquals('Alice', $result->items()[0]->name);
        $this->assertEquals('Bob', $result->items()[1]->name);
        $this->assertEquals('Zara', $result->items()[2]->name);
    }

    /**
     * Test createUser with valid data
     * 
     * Requirements: 3.1, 3.2
     */
    public function test_create_user_with_valid_data(): void
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'role' => 'staff',
        ];

        $user = $this->userService->createUser($data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertEquals('staff', $user->role);
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User',
            'role' => 'staff',
        ]);
    }

    /**
     * Test createUser hashes password
     * 
     * Requirements: 4.3, 7.3, 9.2
     */
    public function test_create_user_hashes_password(): void
    {
        $plainPassword = 'password123';
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => $plainPassword,
            'role' => 'admin',
        ];

        $user = $this->userService->createUser($data);

        // Password should be hashed, not plain text
        $this->assertNotEquals($plainPassword, $user->password);
        $this->assertTrue(Hash::check($plainPassword, $user->password));
    }

    /**
     * Test createUser with different roles
     * 
     * Requirements: 3.2, 3.6
     */
    public function test_create_user_with_different_roles(): void
    {
        $roles = ['admin', 'bendahara', 'staff'];

        foreach ($roles as $role) {
            $data = [
                'name' => "User {$role}",
                'email' => "{$role}@example.com",
                'password' => 'password123',
                'role' => $role,
            ];

            $user = $this->userService->createUser($data);

            $this->assertEquals($role, $user->role);
            $this->assertDatabaseHas('users', [
                'email' => "{$role}@example.com",
                'role' => $role,
            ]);
        }
    }

    /**
     * Test updateUser modifies user data
     * 
     * Requirements: 3.3
     */
    public function test_update_user(): void
    {
        $user = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@example.com',
            'role' => 'staff',
        ]);

        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'role' => 'bendahara',
        ];

        $updatedUser = $this->userService->updateUser($user, $updateData);

        $this->assertEquals('Updated Name', $updatedUser->name);
        $this->assertEquals('updated@example.com', $updatedUser->email);
        $this->assertEquals('bendahara', $updatedUser->role);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'role' => 'bendahara',
        ]);
    }

    /**
     * Test updateUser returns fresh instance
     * 
     * Requirements: 3.3
     */
    public function test_update_user_returns_fresh_instance(): void
    {
        $user = User::factory()->create([
            'name' => 'Original Name',
        ]);

        $updateData = [
            'name' => 'Updated Name',
        ];

        $updatedUser = $this->userService->updateUser($user, $updateData);

        // Should return fresh instance with updated data
        $this->assertNotSame($user, $updatedUser);
        $this->assertEquals('Updated Name', $updatedUser->name);
    }

    /**
     * Test deleteUser removes user from database
     * 
     * Requirements: 3.4
     */
    public function test_delete_user(): void
    {
        $user = User::factory()->create([
            'email' => 'delete@example.com',
        ]);

        $userId = $user->id;

        $result = $this->userService->deleteUser($user);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('users', [
            'id' => $userId,
            'email' => 'delete@example.com',
        ]);
    }

    /**
     * Test resetPassword updates user password
     * 
     * Requirements: 4.1, 4.2
     */
    public function test_reset_password(): void
    {
        $user = User::factory()->create([
            'password' => 'oldpassword',
        ]);

        $newPassword = 'newpassword123';
        $result = $this->userService->resetPassword($user, $newPassword);

        $this->assertTrue($result);
        
        // Refresh user from database
        $user->refresh();
        
        // Verify new password works
        $this->assertTrue(Hash::check($newPassword, $user->password));
        
        // Verify old password doesn't work
        $this->assertFalse(Hash::check('oldpassword', $user->password));
    }

    /**
     * Test resetPassword hashes the new password
     * 
     * Requirements: 4.3, 9.2
     */
    public function test_reset_password_hashes_password(): void
    {
        $user = User::factory()->create();

        $plainPassword = 'newpassword123';
        $this->userService->resetPassword($user, $plainPassword);

        $user->refresh();

        // Password should be hashed, not plain text
        $this->assertNotEquals($plainPassword, $user->password);
        $this->assertTrue(Hash::check($plainPassword, $user->password));
    }

    /**
     * Test isValidRole returns true for valid roles
     * 
     * Requirements: 3.6
     */
    public function test_is_valid_role_returns_true_for_valid_roles(): void
    {
        $validRoles = ['admin', 'bendahara', 'staff'];

        foreach ($validRoles as $role) {
            $this->assertTrue($this->userService->isValidRole($role));
        }
    }

    /**
     * Test isValidRole returns false for invalid roles
     * 
     * Requirements: 3.6
     */
    public function test_is_valid_role_returns_false_for_invalid_roles(): void
    {
        $invalidRoles = ['superadmin', 'user', 'guest', 'manager', ''];

        foreach ($invalidRoles as $role) {
            $this->assertFalse($this->userService->isValidRole($role));
        }
    }

    /**
     * Test getAllUsers with custom per page value
     */
    public function test_get_all_users_with_custom_per_page(): void
    {
        User::factory()->count(30)->create();

        $result = $this->userService->getAllUsers(10);

        $this->assertCount(10, $result->items());
        $this->assertEquals(30, $result->total());
        $this->assertEquals(10, $result->perPage());
    }

    /**
     * Test getAllUsers with default per page value
     */
    public function test_get_all_users_with_default_per_page(): void
    {
        User::factory()->count(20)->create();

        $result = $this->userService->getAllUsers();

        $this->assertEquals(15, $result->perPage());
    }
}
