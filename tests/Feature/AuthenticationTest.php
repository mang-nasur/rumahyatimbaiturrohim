<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Property 1: Valid credentials create session and redirect
     * 
     * For any valid user credentials (email and password), when submitted through 
     * the login form, the system should create an authenticated session and redirect 
     * to the dashboard.
     * 
     * **Validates: Requirements 1.1**
     */
    public function test_valid_credentials_create_session_and_redirect(): void
    {
        // Test with 100 iterations to validate property across different users
        for ($i = 0; $i < 100; $i++) {
            $password = fake()->password(8, 20);
            
            $user = User::factory()->create([
                'password' => $password,
            ]);

            $response = $this->post(route('login'), [
                'email' => $user->email,
                'password' => $password,
            ]);

            // Assert session is created
            $this->assertAuthenticatedAs($user);
            
            // Assert redirect to dashboard
            $response->assertRedirect(route('dashboard'));
            
            // Logout for next iteration
            $this->post(route('logout'));
        }
    }

    /**
     * Property 2: Invalid credentials show error without session
     * 
     * For any invalid credentials (wrong email or wrong password), when submitted 
     * through the login form, the system should display an error message, remain 
     * on the login page, and not create a session.
     * 
     * **Validates: Requirements 1.2**
     */
    public function test_invalid_credentials_show_error_without_session(): void
    {
        // Test with 100 iterations to validate property across different invalid attempts
        for ($i = 0; $i < 100; $i++) {
            $user = User::factory()->create([
                'password' => 'correctpassword',
            ]);

            // Test with wrong password
            $response = $this->post(route('login'), [
                'email' => $user->email,
                'password' => 'wrongpassword',
            ]);

            // Assert no session is created
            $this->assertGuest();
            
            // Assert error message is shown
            $response->assertSessionHasErrors('email');
            
            // Assert remains on login page (redirect back)
            $response->assertRedirect();
        }
        
        // Test with non-existent email
        for ($i = 0; $i < 50; $i++) {
            $response = $this->post(route('login'), [
                'email' => fake()->unique()->safeEmail(),
                'password' => fake()->password(8, 20),
            ]);

            // Assert no session is created
            $this->assertGuest();
            
            // Assert error message is shown
            $response->assertSessionHasErrors('email');
        }
    }

    /**
     * Property 3: Logout destroys session
     * 
     * For any authenticated user, when logout is requested, the system should 
     * destroy the session and redirect to the login page.
     * 
     * **Validates: Requirements 1.3**
     */
    public function test_logout_destroys_session(): void
    {
        // Test with 100 iterations to validate property across different users
        for ($i = 0; $i < 100; $i++) {
            $user = User::factory()->create();

            // Login user
            $this->actingAs($user);
            $this->assertAuthenticated();

            // Logout
            $response = $this->post(route('logout'));

            // Assert session is destroyed
            $this->assertGuest();
            
            // Assert redirect to login page
            $response->assertRedirect(route('login'));
        }
    }

    /**
     * Test login with remember me functionality
     * 
     * Tests that remember me checkbox sets the remember token
     */
    public function test_login_with_remember_me_sets_token(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $password = fake()->password(8, 20);
            
            $user = User::factory()->create([
                'password' => $password,
                'remember_token' => null,
            ]);

            $response = $this->post(route('login'), [
                'email' => $user->email,
                'password' => $password,
                'remember' => true,
            ]);

            // Assert session is created
            $this->assertAuthenticatedAs($user);
            
            // Assert remember token is set
            $user->refresh();
            $this->assertNotNull($user->remember_token);
            
            // Logout for next iteration
            $this->post(route('logout'));
        }
    }

    /**
     * Test login without remember me doesn't require token
     */
    public function test_login_without_remember_me(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $password = fake()->password(8, 20);
            
            $user = User::factory()->create([
                'password' => $password,
            ]);

            $response = $this->post(route('login'), [
                'email' => $user->email,
                'password' => $password,
                'remember' => false,
            ]);

            // Assert session is created
            $this->assertAuthenticatedAs($user);
            
            // Assert redirect to dashboard
            $response->assertRedirect(route('dashboard'));
            
            // Logout for next iteration
            $this->post(route('logout'));
        }
    }

    /**
     * Test validation errors for missing credentials
     */
    public function test_login_validation_requires_email_and_password(): void
    {
        // Test missing email
        $response = $this->post(route('login'), [
            'password' => 'password123',
        ]);
        $response->assertSessionHasErrors('email');

        // Test missing password
        $response = $this->post(route('login'), [
            'email' => 'test@example.com',
        ]);
        $response->assertSessionHasErrors('password');

        // Test invalid email format
        $response = $this->post(route('login'), [
            'email' => 'not-an-email',
            'password' => 'password123',
        ]);
        $response->assertSessionHasErrors('email');
    }

    /**
     * Test authenticated users are redirected from login page
     */
    public function test_authenticated_users_redirected_from_login(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $user = User::factory()->create();

            // Login user
            $this->actingAs($user);

            // Try to access login page
            $response = $this->get(route('login'));

            // Assert redirect to dashboard
            $response->assertRedirect(route('dashboard'));
            
            // Logout for next iteration
            $this->post(route('logout'));
        }
    }

    /**
     * Test session regeneration on login
     */
    public function test_session_regenerates_on_login(): void
    {
        $password = 'password123';
        $user = User::factory()->create([
            'password' => $password,
        ]);

        // Start a session
        $this->get(route('login'));
        $oldSessionId = session()->getId();

        // Login
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => $password,
        ]);

        // Assert session ID changed (regenerated)
        $newSessionId = session()->getId();
        $this->assertNotEquals($oldSessionId, $newSessionId);
    }
}
