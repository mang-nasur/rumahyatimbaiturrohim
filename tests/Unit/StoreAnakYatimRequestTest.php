<?php

namespace Tests\Unit;

use App\Http\Requests\StoreAnakYatimRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class StoreAnakYatimRequestTest extends TestCase
{
    private function validate(array $data): \Illuminate\Validation\Validator
    {
        $request = new StoreAnakYatimRequest();
        return Validator::make($data, $request->rules(), $request->messages());
    }

    /** @test */
    public function it_passes_with_valid_required_fields()
    {
        $data = [
            'nama_lengkap' => 'Ahmad Fauzi',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2010-05-15',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_masuk' => '2020-01-10',
        ];

        $validator = $this->validate($data);
        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function it_fails_when_nama_lengkap_is_missing()
    {
        $data = [
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2010-05-15',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_masuk' => '2020-01-10',
        ];

        $validator = $this->validate($data);
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('nama_lengkap', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_when_tempat_lahir_is_missing()
    {
        $data = [
            'nama_lengkap' => 'Ahmad Fauzi',
            'tanggal_lahir' => '2010-05-15',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_masuk' => '2020-01-10',
        ];

        $validator = $this->validate($data);
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('tempat_lahir', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_when_tanggal_lahir_is_missing()
    {
        $data = [
            'nama_lengkap' => 'Ahmad Fauzi',
            'tempat_lahir' => 'Jakarta',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_masuk' => '2020-01-10',
        ];

        $validator = $this->validate($data);
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('tanggal_lahir', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_when_tanggal_lahir_is_in_future()
    {
        $data = [
            'nama_lengkap' => 'Ahmad Fauzi',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => now()->addDay()->format('Y-m-d'),
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_masuk' => '2020-01-10',
        ];

        $validator = $this->validate($data);
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('tanggal_lahir', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_when_jenis_kelamin_is_missing()
    {
        $data = [
            'nama_lengkap' => 'Ahmad Fauzi',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2010-05-15',
            'tanggal_masuk' => '2020-01-10',
        ];

        $validator = $this->validate($data);
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('jenis_kelamin', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_when_jenis_kelamin_is_invalid()
    {
        $data = [
            'nama_lengkap' => 'Ahmad Fauzi',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2010-05-15',
            'jenis_kelamin' => 'Invalid',
            'tanggal_masuk' => '2020-01-10',
        ];

        $validator = $this->validate($data);
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('jenis_kelamin', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_when_tanggal_masuk_is_missing()
    {
        $data = [
            'nama_lengkap' => 'Ahmad Fauzi',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2010-05-15',
            'jenis_kelamin' => 'Laki-laki',
        ];

        $validator = $this->validate($data);
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('tanggal_masuk', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_when_nomor_telepon_wali_has_invalid_characters()
    {
        $data = [
            'nama_lengkap' => 'Ahmad Fauzi',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2010-05-15',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_masuk' => '2020-01-10',
            'nomor_telepon_wali' => '0812-3456-7890abc',
        ];

        $validator = $this->validate($data);
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('nomor_telepon_wali', $validator->errors()->toArray());
    }

    /** @test */
    public function it_passes_with_valid_nomor_telepon_wali()
    {
        $data = [
            'nama_lengkap' => 'Ahmad Fauzi',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2010-05-15',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_masuk' => '2020-01-10',
            'nomor_telepon_wali' => '+62812-3456-7890',
        ];

        $validator = $this->validate($data);
        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function it_fails_when_string_fields_exceed_max_length()
    {
        $data = [
            'nama_lengkap' => str_repeat('a', 256), // Exceeds 255
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2010-05-15',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_masuk' => '2020-01-10',
        ];

        $validator = $this->validate($data);
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('nama_lengkap', $validator->errors()->toArray());
    }

    /** @test */
    public function it_has_indonesian_error_messages()
    {
        $data = [
            'tanggal_lahir' => '2010-05-15',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_masuk' => '2020-01-10',
        ];

        $validator = $this->validate($data);
        $errors = $validator->errors();
        
        $this->assertStringContainsString('wajib diisi', $errors->first('nama_lengkap'));
        $this->assertStringContainsString('wajib diisi', $errors->first('tempat_lahir'));
    }
}
