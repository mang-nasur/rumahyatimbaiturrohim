<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authService = new AuthService();
    }

    /**
     * Test successful authentication
     * 
     * Requirements: 1.1
     */
    public function test_successful_authentication(): void
    {
        $password = 'password123';
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => $password,
        ]);

        $credentials = [
            'email' => 'test@example.com',
            'password' => $password,
        ];

        $result = $this->authService->attempt($credentials, false);

        $this->assertTrue($result);
        $this->assertTrue($this->authService->check());
        $this->assertNotNull($this->authService->user());
        $this->assertEquals($user->id, $this->authService->user()->id);
        $this->assertEquals($user->email, $this->authService->user()->email);
    }

    /**
     * Test failed authentication with wrong password
     * 
     * Requirements: 1.2
     */
    public function test_failed_authentication_wrong_password(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => 'correctpassword',
        ]);

        $credentials = [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ];

        $result = $this->authService->attempt($credentials, false);

        $this->assertFalse($result);
        $this->assertFalse($this->authService->check());
        $this->assertNull($this->authService->user());
    }

    /**
     * Test failed authentication with non-existent email
     * 
     * Requirements: 1.2
     */
    public function test_failed_authentication_nonexistent_email(): void
    {
        $credentials = [
            'email' => 'nonexistent@example.com',
            'password' => 'somepassword',
        ];

        $result = $this->authService->attempt($credentials, false);

        $this->assertFalse($result);
        $this->assertFalse($this->authService->check());
        $this->assertNull($this->authService->user());
    }

    /**
     * Test logout clears session
     * 
     * Requirements: 1.3
     */
    public function test_logout_clears_session(): void
    {
        $password = 'password123';
        $user = User::factory()->create([
            'password' => $password,
        ]);

        // First, authenticate the user
        $credentials = [
            'email' => $user->email,
            'password' => $password,
        ];

        $this->authService->attempt($credentials, false);
        
        // Verify user is authenticated
        $this->assertTrue($this->authService->check());
        $this->assertNotNull($this->authService->user());

        // Logout
        $this->authService->logout();

        // Verify user is no longer authenticated
        $this->assertFalse($this->authService->check());
        $this->assertNull($this->authService->user());
    }

    /**
     * Test check method returns false when not authenticated
     */
    public function test_check_returns_false_when_not_authenticated(): void
    {
        $this->assertFalse($this->authService->check());
    }

    /**
     * Test user method returns null when not authenticated
     */
    public function test_user_returns_null_when_not_authenticated(): void
    {
        $this->assertNull($this->authService->user());
    }

    /**
     * Test authentication with remember me
     * 
     * Requirements: 1.4
     */
    public function test_authentication_with_remember_me(): void
    {
        $password = 'password123';
        $user = User::factory()->create([
            'password' => $password,
            'remember_token' => null,
        ]);

        $credentials = [
            'email' => $user->email,
            'password' => $password,
        ];

        $result = $this->authService->attempt($credentials, true);

        $this->assertTrue($result);
        $this->assertTrue($this->authService->check());
        
        // Verify remember token was set
        $user->refresh();
        $this->assertNotNull($user->remember_token);
    }

    /**
     * Test authentication without remember me
     */
    public function test_authentication_without_remember_me(): void
    {
        $password = 'password123';
        $user = User::factory()->create([
            'password' => $password,
        ]);

        $credentials = [
            'email' => $user->email,
            'password' => $password,
        ];

        $result = $this->authService->attempt($credentials, false);

        $this->assertTrue($result);
        $this->assertTrue($this->authService->check());
        $this->assertEquals($user->id, $this->authService->user()->id);
    }

    /**
     * Test multiple failed authentication attempts
     */
    public function test_multiple_failed_authentication_attempts(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => 'correctpassword',
        ]);

        $wrongCredentials = [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ];

        // Try multiple times
        for ($i = 0; $i < 3; $i++) {
            $result = $this->authService->attempt($wrongCredentials, false);
            $this->assertFalse($result);
            $this->assertFalse($this->authService->check());
        }

        // Verify correct credentials still work
        $correctCredentials = [
            'email' => 'test@example.com',
            'password' => 'correctpassword',
        ];

        $result = $this->authService->attempt($correctCredentials, false);
        $this->assertTrue($result);
    }
}
