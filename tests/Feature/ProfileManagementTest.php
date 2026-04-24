<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Property 20: Profile displays user data
     * 
     * For any authenticated user viewing their profile, the system should 
     * display their name, email, and role.
     * 
     * **Validates: Requirements 5.1**
     */
    public function test_profile_displays_user_data(): void
    {
        // Test with 100 iterations to validate property across different users
        for ($i = 0; $i < 100; $i++) {
            $user = User::factory()->create();

            $response = $this->actingAs($user)->get(route('profile.show'));

            // Assert response is successful
            $response->assertOk();
            
            // Assert user data is displayed
            $response->assertSee($user->name);
            $response->assertSee($user->email);
            $response->assertSee(ucfirst($user->role));
            
            // Logout for next iteration
            $this->post(route('logout'));
        }
    }

    /**
     * Property 21: Profile update restricts role modification
     * 
     * For any authenticated user updating their profile, the system should 
     * allow changing name and email but not role.
     * 
     * **Validates: Requirements 5.2**
     */
    public function test_profile_update_restricts_role_modification(): void
    {
        // Test with 100 iterations to validate property across different users
        for ($i = 0; $i < 100; $i++) {
            $user = User::factory()->create([
                'role' => fake()->randomElement(['admin', 'bendahara', 'staff']),
            ]);
            
            $originalRole = $user->role;
            $newName = fake()->name();
            $newEmail = fake()->unique()->safeEmail();
            $attemptedRole = fake()->randomElement(['admin', 'bendahara', 'staff']);

            $response = $this->actingAs($user)->put(route('profile.update'), [
                'name' => $newName,
                'email' => $newEmail,
                'role' => $attemptedRole, // Attempt to change role
            ]);

            // Assert redirect with success
            $response->assertRedirect(route('profile.show'));
            
            // Refresh user from database
            $user->refresh();
            
            // Assert name and email are updated
            $this->assertEquals($newName, $user->name);
            $this->assertEquals($newEmail, $user->email);
            
            // Assert role remains unchanged
            $this->assertEquals($originalRole, $user->role);
            
            // Logout for next iteration
            $this->post(route('logout'));
        }
    }

    /**
     * Property 22: Password change requires current password
     * 
     * For any password change attempt, the system should reject the request 
     * if the current password is incorrect.
     * 
     * **Validates: Requirements 5.3**
     */
    public function test_password_change_requires_current_password(): void
    {
        // Test with 100 iterations to validate property across different users
        for ($i = 0; $i < 100; $i++) {
            $correctPassword = fake()->password(8, 20);
            $user = User::factory()->create([
                'password' => $correctPassword,
            ]);

            $wrongPassword = fake()->password(8, 20);
            $newPassword = fake()->password(8, 20);

            $response = $this->actingAs($user)->post(route('profile.change-password.update'), [
                'current_password' => $wrongPassword,
                'password' => $newPassword,
                'password_confirmation' => $newPassword,
            ]);

            // Assert validation error
            $response->assertSessionHasErrors('current_password');
            
            // Refresh user from database
            $user->refresh();
            
            // Assert password remains unchanged
            $this->assertTrue(Hash::check($correctPassword, $user->password));
            $this->assertFalse(Hash::check($newPassword, $user->password));
            
            // Logout for next iteration
            $this->post(route('logout'));
        }
    }

    /**
     * Property 23: Password change validates strength
     * 
     * For any password change attempt, the system should reject new passwords 
     * shorter than 8 characters.
     * 
     * **Validates: Requirements 5.4**
     */
    public function test_password_change_validates_strength(): void
    {
        // Test with 100 iterations to validate property across different weak passwords
        for ($i = 0; $i < 100; $i++) {
            $currentPassword = fake()->password(8, 20);
            $user = User::factory()->create([
                'password' => $currentPassword,
            ]);

            // Generate weak password (less than 8 characters)
            $weakPassword = fake()->password(1, 7);

            $response = $this->actingAs($user)->post(route('profile.change-password.update'), [
                'current_password' => $currentPassword,
                'password' => $weakPassword,
                'password_confirmation' => $weakPassword,
            ]);

            // Assert validation error
            $response->assertSessionHasErrors('password');
            
            // Refresh user from database
            $user->refresh();
            
            // Assert password remains unchanged
            $this->assertTrue(Hash::check($currentPassword, $user->password));
            
            // Logout for next iteration
            $this->post(route('logout'));
        }
    }

    /**
     * Property 24: Email update validates uniqueness
     * 
     * For any email update attempt, the system should reject emails that 
     * already exist for other users.
     * 
     * **Validates: Requirements 5.5**
     */
    public function test_email_update_validates_uniqueness(): void
    {
        // Test with 100 iterations to validate property across different users
        for ($i = 0; $i < 100; $i++) {
            $existingUser = User::factory()->create();
            $user = User::factory()->create();

            $response = $this->actingAs($user)->put(route('profile.update'), [
                'name' => fake()->name(),
                'email' => $existingUser->email, // Try to use existing email
            ]);

            // Assert validation error
            $response->assertSessionHasErrors('email');
            
            // Refresh user from database
            $user->refresh();
            
            // Assert email remains unchanged
            $this->assertNotEquals($existingUser->email, $user->email);
            
            // Logout for next iteration
            $this->post(route('logout'));
        }
    }

    /**
     * Test successful password change with correct current password
     */
    public function test_successful_password_change(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $currentPassword = fake()->password(8, 20);
            $user = User::factory()->create([
                'password' => $currentPassword,
            ]);

            $newPassword = fake()->password(8, 20);

            $response = $this->actingAs($user)->post(route('profile.change-password.update'), [
                'current_password' => $currentPassword,
                'password' => $newPassword,
                'password_confirmation' => $newPassword,
            ]);

            // Assert redirect with success
            $response->assertRedirect(route('profile.show'));
            $response->assertSessionHas('success');
            
            // Refresh user from database
            $user->refresh();
            
            // Assert password is changed
            $this->assertFalse(Hash::check($currentPassword, $user->password));
            $this->assertTrue(Hash::check($newPassword, $user->password));
            
            // Logout for next iteration
            $this->post(route('logout'));
        }
    }

    /**
     * Test successful profile update with valid data
     */
    public function test_successful_profile_update(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $user = User::factory()->create();

            $newName = fake()->name();
            $newEmail = fake()->unique()->safeEmail();

            $response = $this->actingAs($user)->put(route('profile.update'), [
                'name' => $newName,
                'email' => $newEmail,
            ]);

            // Assert redirect with success
            $response->assertRedirect(route('profile.show'));
            $response->assertSessionHas('success');
            
            // Refresh user from database
            $user->refresh();
            
            // Assert data is updated
            $this->assertEquals($newName, $user->name);
            $this->assertEquals($newEmail, $user->email);
            
            // Logout for next iteration
            $this->post(route('logout'));
        }
    }

    /**
     * Test profile update allows same email for current user
     */
    public function test_profile_update_allows_same_email(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $user = User::factory()->create();

            $newName = fake()->name();

            $response = $this->actingAs($user)->put(route('profile.update'), [
                'name' => $newName,
                'email' => $user->email, // Keep same email
            ]);

            // Assert redirect with success
            $response->assertRedirect(route('profile.show'));
            $response->assertSessionHas('success');
            
            // Refresh user from database
            $user->refresh();
            
            // Assert name is updated
            $this->assertEquals($newName, $user->name);
            
            // Logout for next iteration
            $this->post(route('logout'));
        }
    }

    /**
     * Test unauthenticated users cannot access profile
     */
    public function test_unauthenticated_users_cannot_access_profile(): void
    {
        $response = $this->get(route('profile.show'));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('profile.edit'));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('profile.change-password'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test profile validation requires name and email
     */
    public function test_profile_validation_requires_name_and_email(): void
    {
        $user = User::factory()->create();

        // Test missing name
        $response = $this->actingAs($user)->put(route('profile.update'), [
            'email' => fake()->safeEmail(),
        ]);
        $response->assertSessionHasErrors('name');

        // Test missing email
        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => fake()->name(),
        ]);
        $response->assertSessionHasErrors('email');

        // Test invalid email format
        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => fake()->name(),
            'email' => 'not-an-email',
        ]);
        $response->assertSessionHasErrors('email');
    }

    /**
     * Test password change validation requires all fields
     */
    public function test_password_change_validation_requires_all_fields(): void
    {
        $user = User::factory()->create([
            'password' => 'password123',
        ]);

        // Test missing current password
        $response = $this->actingAs($user)->post(route('profile.change-password.update'), [
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);
        $response->assertSessionHasErrors('current_password');

        // Test missing new password
        $response = $this->actingAs($user)->post(route('profile.change-password.update'), [
            'current_password' => 'password123',
        ]);
        $response->assertSessionHasErrors('password');

        // Test password confirmation mismatch
        $response = $this->actingAs($user)->post(route('profile.change-password.update'), [
            'current_password' => 'password123',
            'password' => 'newpassword123',
            'password_confirmation' => 'differentpassword',
        ]);
        $response->assertSessionHasErrors('password');
    }
}
