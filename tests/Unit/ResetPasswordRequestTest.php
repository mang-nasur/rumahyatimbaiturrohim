<?php

namespace Tests\Unit;

use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class ResetPasswordRequestTest extends TestCase
{
    use RefreshDatabase;

    private function validate(array $data): \Illuminate\Validation\Validator
    {
        $request = new ResetPasswordRequest();
        return Validator::make($data, $request->rules(), $request->messages());
    }

    /** @test */
    public function it_passes_with_valid_data()
    {
        $data = [
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ];

        $validator = $this->validate($data);
        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function it_fails_when_password_is_missing()
    {
        $data = [
            'password_confirmation' => 'newpassword123',
        ];

        $validator = $this->validate($data);
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_when_password_is_too_short()
    {
        $data = [
            'password' => 'short',
            'password_confirmation' => 'short',
        ];

        $validator = $this->validate($data);
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
        $this->assertStringContainsString('minimal 8 karakter', $validator->errors()->first('password'));
    }

    /** @test */
    public function it_fails_when_password_confirmation_does_not_match()
    {
        $data = [
            'password' => 'newpassword123',
            'password_confirmation' => 'different123',
        ];

        $validator = $this->validate($data);
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
        $this->assertStringContainsString('tidak cocok', $validator->errors()->first('password'));
    }

    /** @test */
    public function it_fails_when_password_confirmation_is_missing()
    {
        $data = [
            'password' => 'newpassword123',
        ];

        $validator = $this->validate($data);
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
    }

    /** @test */
    public function it_passes_with_exactly_8_characters()
    {
        $data = [
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ];

        $validator = $this->validate($data);
        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function it_has_indonesian_error_messages()
    {
        $data = [
            'password' => 'short',
            'password_confirmation' => 'different',
        ];

        $validator = $this->validate($data);
        $errors = $validator->errors();
        
        $this->assertStringContainsString('minimal 8 karakter', $errors->first('password'));
    }
}
