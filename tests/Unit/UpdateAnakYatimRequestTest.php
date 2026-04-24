<?php

namespace Tests\Unit;

use App\Http\Requests\UpdateAnakYatimRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class UpdateAnakYatimRequestTest extends TestCase
{
    /**
     * Test that valid data passes validation.
     */
    public function test_valid_data_passes_validation(): void
    {
        $request = new UpdateAnakYatimRequest();
        $validator = Validator::make([
            'nama_lengkap' => 'Ahmad Fauzi',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2010-05-15',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Jl. Merdeka No. 123',
            'nama_ayah' => 'Budi Santoso',
            'status_ayah' => 'Meninggal',
            'nama_ibu' => 'Siti Aminah',
            'status_ibu' => 'Hidup',
            'nomor_telepon_wali' => '+62812345678',
            'tanggal_masuk' => '2020-01-10',
            'pendidikan_terakhir' => 'SD',
            'sekolah_saat_ini' => 'SDN 01 Jakarta',
        ], $request->rules());

        $this->assertTrue($validator->passes());
    }

    /**
     * Test that required fields are validated.
     */
    public function test_required_fields_validation(): void
    {
        $request = new UpdateAnakYatimRequest();
        $validator = Validator::make([], $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->errors()->has('nama_lengkap'));
        $this->assertTrue($validator->errors()->has('tempat_lahir'));
        $this->assertTrue($validator->errors()->has('tanggal_lahir'));
        $this->assertTrue($validator->errors()->has('jenis_kelamin'));
        $this->assertTrue($validator->errors()->has('tanggal_masuk'));
    }

    /**
     * Test that tanggal_lahir must be before today.
     */
    public function test_tanggal_lahir_must_be_before_today(): void
    {
        $request = new UpdateAnakYatimRequest();
        $validator = Validator::make([
            'nama_lengkap' => 'Ahmad Fauzi',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => now()->addDay()->format('Y-m-d'),
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_masuk' => '2020-01-10',
        ], $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->errors()->has('tanggal_lahir'));
    }

    /**
     * Test that jenis_kelamin must be valid.
     */
    public function test_jenis_kelamin_validation(): void
    {
        $request = new UpdateAnakYatimRequest();
        $validator = Validator::make([
            'nama_lengkap' => 'Ahmad Fauzi',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2010-05-15',
            'jenis_kelamin' => 'Invalid',
            'tanggal_masuk' => '2020-01-10',
        ], $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->errors()->has('jenis_kelamin'));
    }

    /**
     * Test that nomor_telepon_wali format is validated.
     */
    public function test_nomor_telepon_wali_format_validation(): void
    {
        $request = new UpdateAnakYatimRequest();
        
        // Valid formats
        $validNumbers = ['+62812345678', '081234567890', '0812-3456-7890'];
        foreach ($validNumbers as $number) {
            $validator = Validator::make([
                'nama_lengkap' => 'Ahmad Fauzi',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2010-05-15',
                'jenis_kelamin' => 'Laki-laki',
                'tanggal_masuk' => '2020-01-10',
                'nomor_telepon_wali' => $number,
            ], $request->rules());
            
            $this->assertTrue($validator->passes(), "Valid number {$number} should pass");
        }

        // Invalid format
        $validator = Validator::make([
            'nama_lengkap' => 'Ahmad Fauzi',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2010-05-15',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_masuk' => '2020-01-10',
            'nomor_telepon_wali' => 'abc123',
        ], $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->errors()->has('nomor_telepon_wali'));
    }

    /**
     * Test that max length validation works.
     */
    public function test_max_length_validation(): void
    {
        $request = new UpdateAnakYatimRequest();
        $validator = Validator::make([
            'nama_lengkap' => str_repeat('a', 256),
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2010-05-15',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_masuk' => '2020-01-10',
        ], $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->errors()->has('nama_lengkap'));
    }

    /**
     * Test that foto validation works.
     */
    public function test_foto_is_optional(): void
    {
        $request = new UpdateAnakYatimRequest();
        $validator = Validator::make([
            'nama_lengkap' => 'Ahmad Fauzi',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2010-05-15',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_masuk' => '2020-01-10',
        ], $request->rules());

        $this->assertTrue($validator->passes());
    }

    /**
     * Test that custom error messages are defined.
     */
    public function test_custom_error_messages_exist(): void
    {
        $request = new UpdateAnakYatimRequest();
        $messages = $request->messages();

        $this->assertArrayHasKey('nama_lengkap.required', $messages);
        $this->assertArrayHasKey('tanggal_lahir.before', $messages);
        $this->assertArrayHasKey('jenis_kelamin.in', $messages);
        $this->assertArrayHasKey('nomor_telepon_wali.regex', $messages);
        $this->assertArrayHasKey('foto.max', $messages);
    }
}
