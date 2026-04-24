<?php

namespace Tests\Unit;

use App\Http\Middleware\RoleMiddleware;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Property 7: Admin has universal access
     * 
     * For any route in the application, when accessed by a user with admin role,
     * the system should grant access.
     * 
     * **Validates: Requirements 2.2**
     */
    public function test_admin_has_universal_access(): void
    {
        // Test with 100 iterations to validate property across different scenarios
        for ($i = 0; $i < 100; $i++) {
            $admin = User::factory()->admin()->create();
            
            // Test with random role requirements - admin should pass regardless
            $requiredRoles = fake()->randomElements(['admin', 'bendahara', 'staff'], fake()->numberBetween(1, 3));
            
            $this->actingAs($admin);
            
            $request = Request::create('/test', 'GET');
            $middleware = new RoleMiddleware();
            
            $response = $middleware->handle($request, function ($req) {
                return new Response('Success');
            }, ...$requiredRoles);
            
            // Admin should always pass regardless of required roles
            $this->assertEquals('Success', $response->getContent());
            $this->assertEquals(200, $response->getStatusCode());
        }
    }

    /**
     * Property 8: Role-based access control enforced
     * 
     * For any user and feature combination, the system should grant or deny access
     * according to the role permissions matrix.
     * 
     * **Validates: Requirements 2.3, 2.4, 2.5, 2.6, 2.7**
     */
    public function test_role_based_access_control_enforced(): void
    {
        // Test bendahara access
        for ($i = 0; $i < 50; $i++) {
            $bendahara = User::factory()->bendahara()->create();
            $this->actingAs($bendahara);
            
            $request = Request::create('/test', 'GET');
            $middleware = new RoleMiddleware();
            
            // Bendahara should access routes requiring bendahara role
            $response = $middleware->handle($request, function ($req) {
                return new Response('Success');
            }, 'bendahara');
            
            $this->assertEquals('Success', $response->getContent());
            
            // Bendahara should also access routes allowing multiple roles including bendahara
            $response = $middleware->handle($request, function ($req) {
                return new Response('Success');
            }, 'admin', 'bendahara');
            
            $this->assertEquals('Success', $response->getContent());
        }
        
        // Test staff access
        for ($i = 0; $i < 50; $i++) {
            $staff = User::factory()->staff()->create();
            $this->actingAs($staff);
            
            $request = Request::create('/test', 'GET');
            $middleware = new RoleMiddleware();
            
            // Staff should access routes requiring staff role
            $response = $middleware->handle($request, function ($req) {
                return new Response('Success');
            }, 'staff');
            
            $this->assertEquals('Success', $response->getContent());
            
            // Staff should also access routes allowing multiple roles including staff
            $response = $middleware->handle($request, function ($req) {
                return new Response('Success');
            }, 'admin', 'staff');
            
            $this->assertEquals('Success', $response->getContent());
        }
    }

    /**
     * Property 9: Unauthorized access redirects with error
     * 
     * For any user attempting to access a feature without proper role permissions,
     * the system should redirect to an unauthorized page with an error message.
     * 
     * **Validates: Requirements 2.8**
     */
    public function test_unauthorized_access_redirects_with_error(): void
    {
        // Test with 100 iterations
        for ($i = 0; $i < 100; $i++) {
            // Create user with random role
            $userRole = fake()->randomElement(['bendahara', 'staff']);
            $user = User::factory()->create(['role' => $userRole]);
            
            // Determine a role the user doesn't have
            $deniedRole = $userRole === 'bendahara' ? 'staff' : 'bendahara';
            
            $this->actingAs($user);
            
            $request = Request::create('/test', 'GET');
            $middleware = new RoleMiddleware();
            
            try {
                $middleware->handle($request, function ($req) {
                    return new Response('Success');
                }, $deniedRole);
                
                // Should not reach here
                $this->fail('Expected 403 exception was not thrown');
            } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
                // Assert 403 Forbidden status
                $this->assertEquals(403, $e->getStatusCode());
                
                // Assert error message in Bahasa Indonesia
                $this->assertEquals('Anda tidak memiliki akses ke halaman ini', $e->getMessage());
            }
        }
    }

    /**
     * Test that unauthenticated users are redirected to login
     */
    public function test_unauthenticated_users_redirected_to_login(): void
    {
        for ($i = 0; $i < 50; $i++) {
            $request = Request::create('/test', 'GET');
            $middleware = new RoleMiddleware();
            
            $response = $middleware->handle($request, function ($req) {
                return new Response('Success');
            }, 'admin');
            
            // Should redirect to login
            $this->assertEquals(302, $response->getStatusCode());
            $this->assertTrue($response->isRedirect(route('login')));
        }
    }

    /**
     * Test that middleware works with multiple role requirements
     */
    public function test_middleware_with_multiple_role_requirements(): void
    {
        for ($i = 0; $i < 50; $i++) {
            // Test bendahara accessing route that allows admin or bendahara
            $bendahara = User::factory()->bendahara()->create();
            $this->actingAs($bendahara);
            
            $request = Request::create('/test', 'GET');
            $middleware = new RoleMiddleware();
            
            $response = $middleware->handle($request, function ($req) {
                return new Response('Success');
            }, 'admin', 'bendahara');
            
            $this->assertEquals('Success', $response->getContent());
            
            // Test staff accessing route that allows admin or staff
            $staff = User::factory()->staff()->create();
            $this->actingAs($staff);
            
            $response = $middleware->handle($request, function ($req) {
                return new Response('Success');
            }, 'admin', 'staff');
            
            $this->assertEquals('Success', $response->getContent());
        }
    }

    /**
     * Test that staff cannot access bendahara-only routes
     */
    public function test_staff_cannot_access_bendahara_only_routes(): void
    {
        for ($i = 0; $i < 50; $i++) {
            $staff = User::factory()->staff()->create();
            $this->actingAs($staff);
            
            $request = Request::create('/test', 'GET');
            $middleware = new RoleMiddleware();
            
            try {
                $middleware->handle($request, function ($req) {
                    return new Response('Success');
                }, 'bendahara');
                
                $this->fail('Expected 403 exception was not thrown');
            } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
                $this->assertEquals(403, $e->getStatusCode());
            }
        }
    }

    /**
     * Test that bendahara cannot access staff-only routes
     */
    public function test_bendahara_cannot_access_staff_only_routes(): void
    {
        for ($i = 0; $i < 50; $i++) {
            $bendahara = User::factory()->bendahara()->create();
            $this->actingAs($bendahara);
            
            $request = Request::create('/test', 'GET');
            $middleware = new RoleMiddleware();
            
            try {
                $middleware->handle($request, function ($req) {
                    return new Response('Success');
                }, 'staff');
                
                $this->fail('Expected 403 exception was not thrown');
            } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
                $this->assertEquals(403, $e->getStatusCode());
            }
        }
    }
}
