<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AuthServiceRememberMeTest extends TestCase
{
    use RefreshDatabase;

    protected AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authService = new AuthService();
    }

    /**
     * Property 4: Remember me sets token
     * 
     * For any valid credentials with remember me enabled, when login is successful,
     * the system should set a remember token that persists the session.
     * 
     * **Validates: Requirements 1.4**
     */
    public function test_remember_me_sets_token_property(): void
    {
        // Test with 100 iterations to validate property across different users
        for ($i = 0; $i < 100; $i++) {
            // Logout any previous session
            Auth::logout();
            
            $password = fake()->password(8, 20);
            
            $user = User::factory()->create([
                'password' => $password,
                'remember_token' => null, // Start with no token
            ]);

            // Verify user starts with no remember token
            $this->assertNull($user->fresh()->remember_token);

            // Attempt login with remember me enabled
            $credentials = [
                'email' => $user->email,
                'password' => $password,
            ];

            $result = $this->authService->attempt($credentials, true);

            // Assert authentication was successful
            $this->assertTrue($result, "Authentication should succeed for iteration {$i}");
            
            // Assert user is authenticated
            $this->assertTrue($this->authService->check());
            
            // Assert the authenticated user is the correct one
            $this->assertEquals($user->id, $this->authService->user()->id);

            // Refresh user from database and verify remember token was set
            $user->refresh();
            $this->assertNotNull(
                $user->remember_token,
                "Remember token should be set when remember me is enabled (iteration {$i})"
            );
            
            // Verify token is a non-empty string
            $this->assertIsString($user->remember_token);
            $this->assertNotEmpty($user->remember_token);
            
            // Verify token has reasonable length (Laravel uses 60 character tokens)
            $this->assertGreaterThan(10, strlen($user->remember_token));
        }
    }

    /**
     * Test that remember me false does not require token persistence
     * (token may or may not be set, but authentication should work)
     */
    public function test_remember_me_false_authenticates_successfully(): void
    {
        for ($i = 0; $i < 100; $i++) {
            Auth::logout();
            
            $password = fake()->password(8, 20);
            
            $user = User::factory()->create([
                'password' => $password,
            ]);

            $credentials = [
                'email' => $user->email,
                'password' => $password,
            ];

            // Attempt login without remember me
            $result = $this->authService->attempt($credentials, false);

            // Assert authentication was successful
            $this->assertTrue($result, "Authentication should succeed without remember me (iteration {$i})");
            
            // Assert user is authenticated
            $this->assertTrue($this->authService->check());
            
            // Assert the authenticated user is the correct one
            $this->assertEquals($user->id, $this->authService->user()->id);
        }
    }
}
