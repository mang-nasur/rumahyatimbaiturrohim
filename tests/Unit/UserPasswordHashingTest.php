<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserPasswordHashingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Property 18: Passwords are hashed before storage
     * 
     * For any password (new user, reset, or change), the system should hash 
     * the password using bcrypt before storing in the database.
     * 
     * **Validates: Requirements 4.3, 7.3, 9.2**
     */
    public function test_passwords_are_hashed_before_storage(): void
    {
        // Test with 100 iterations to validate property across different inputs
        for ($i = 0; $i < 100; $i++) {
            $plainPassword = fake()->password(8, 20);
            
            $user = User::create([
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'password' => $plainPassword,
                'role' => fake()->randomElement(['admin', 'bendahara', 'staff']),
            ]);

            // Refresh to get the actual database value
            $user->refresh();

            // Assert password is not stored as plain text
            $this->assertNotEquals($plainPassword, $user->password);
            
            // Assert password is hashed using bcrypt
            $this->assertTrue(Hash::check($plainPassword, $user->password));
            
            // Assert password hash starts with bcrypt identifier
            $this->assertStringStartsWith('$2y$', $user->password);
        }
    }

    /**
     * Test password hashing when creating user with different passwords
     */
    public function test_password_hashing_on_multiple_creates(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $newPassword = fake()->password(8, 20);
            
            $user = User::create([
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'password' => $newPassword,
                'role' => fake()->randomElement(['admin', 'bendahara', 'staff']),
            ]);
            
            $user->refresh();

            // Assert new password is hashed
            $this->assertNotEquals($newPassword, $user->password);
            $this->assertTrue(Hash::check($newPassword, $user->password));
            $this->assertStringStartsWith('$2y$', $user->password);
        }
    }

    /**
     * Test that different passwords produce different hashes
     */
    public function test_different_passwords_produce_different_hashes(): void
    {
        $hashes = [];
        
        for ($i = 0; $i < 50; $i++) {
            $password = fake()->password(8, 20);
            
            $user = User::create([
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'password' => $password,
                'role' => fake()->randomElement(['admin', 'bendahara', 'staff']),
            ]);

            $user->refresh();
            
            // Ensure this hash is unique
            $this->assertNotContains($user->password, $hashes);
            $hashes[] = $user->password;
        }
    }

    /**
     * Test that same password produces different hashes (bcrypt salt)
     */
    public function test_same_password_produces_different_hashes_due_to_salt(): void
    {
        $password = 'SamePassword123!';
        $hashes = [];
        
        for ($i = 0; $i < 10; $i++) {
            $user = User::create([
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'password' => $password,
                'role' => fake()->randomElement(['admin', 'bendahara', 'staff']),
            ]);

            $user->refresh();
            
            // All hashes should verify the same password
            $this->assertTrue(Hash::check($password, $user->password));
            
            // But hashes should be different due to salt
            $this->assertNotContains($user->password, $hashes);
            $hashes[] = $user->password;
        }
    }
}
