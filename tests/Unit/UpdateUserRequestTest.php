<?php

namespace Tests\Unit;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class UpdateUserRequestTest extends TestCase
{
    use RefreshDatabase;

    private function validate(array $data, User $user): \Illuminate\Validation\Validator
    {
        $request = new UpdateUserRequest();
        $request->setUserResolver(fn() => $user);
        $request->merge(['user' => $user]);
        
        return Validator::make($data, $request->rules(), $request->messages());
    }

    /** @test */
    public function it_passes_with_valid_data()
    {
        $user = User::factory()->create();

        $data = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'role' => 'staff',
        ];

        $validator = $this->validate($data, $user);
        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function it_fails_when_name_is_missing()
    {
        $user = User::factory()->create();

        $data = [
            'email' => 'updated@example.com',
            'role' => 'staff',
        ];

        $validator = $this->validate($data, $user);
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_when_email_is_missing()
    {
        $user = User::factory()->create();

        $data = [
            'name' => 'Updated Name',
            'role' => 'staff',
        ];

        $validator = $this->validate($data, $user);
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_when_email_format_is_invalid()
    {
        $user = User::factory()->create();

        $data = [
            'name' => 'Updated Name',
            'email' => 'invalid-email',
            'role' => 'staff',
        ];

        $validator = $this->validate($data, $user);
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    /** @test */
    public function it_allows_same_email_for_same_user()
    {
        $user = User::factory()->create(['email' => 'same@example.com']);

        $data = [
            'name' => 'Updated Name',
            'email' => 'same@example.com',
            'role' => 'staff',
        ];

        $validator = $this->validate($data, $user);
        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function it_fails_when_email_exists_for_different_user()
    {
        $existingUser = User::factory()->create(['email' => 'existing@example.com']);
        $user = User::factory()->create(['email' => 'user@example.com']);

        $data = [
            'name' => 'Updated Name',
            'email' => 'existing@example.com',
            'role' => 'staff',
        ];

        $validator = $this->validate($data, $user);
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_when_role_is_missing()
    {
        $user = User::factory()->create();

        $data = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ];

        $validator = $this->validate($data, $user);
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('role', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_when_role_is_invalid()
    {
        $user = User::factory()->create();

        $data = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'role' => 'invalid_role',
        ];

        $validator = $this->validate($data, $user);
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('role', $validator->errors()->toArray());
    }

    /** @test */
    public function it_passes_with_all_valid_roles()
    {
        $roles = ['admin', 'bendahara', 'staff'];

        foreach ($roles as $role) {
            $user = User::factory()->create();

            $data = [
                'name' => 'Updated Name',
                'email' => "updated{$role}@example.com",
                'role' => $role,
            ];

            $validator = $this->validate($data, $user);
            $this->assertTrue($validator->passes(), "Role {$role} should be valid");
        }
    }

    /** @test */
    public function it_fails_when_name_exceeds_max_length()
    {
        $user = User::factory()->create();

        $data = [
            'name' => str_repeat('a', 256),
            'email' => 'updated@example.com',
            'role' => 'staff',
        ];

        $validator = $this->validate($data, $user);
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
    }

    /** @test */
    public function it_has_indonesian_error_messages()
    {
        $user = User::factory()->create();

        $data = [
            'role' => 'invalid',
        ];

        $validator = $this->validate($data, $user);
        $errors = $validator->errors();
        
        $this->assertStringContainsString('wajib diisi', $errors->first('name'));
        $this->assertStringContainsString('wajib diisi', $errors->first('email'));
        $this->assertStringContainsString('tidak valid', $errors->first('role'));
    }
}
