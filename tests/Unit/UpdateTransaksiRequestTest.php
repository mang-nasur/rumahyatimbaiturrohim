<?php

namespace Tests\Unit;

use App\Http\Requests\UpdateTransaksiRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class UpdateTransaksiRequestTest extends TestCase
{
    private function validate(array $data): \Illuminate\Validation\Validator
    {
        $request = new UpdateTransaksiRequest();
        return Validator::make($data, $request->rules(), $request->messages());
    }

    /** @test */
    public function it_passes_with_valid_data()
    {
        $data = [
            'tanggal' => now()->format('Y-m-d'),
            'jenis' => 'pengeluaran',
            'kategori' => 'Kebutuhan Anak',
            'jumlah' => 50000,
            'keterangan' => 'Pembelian buku pelajaran',
        ];

        $validator = $this->validate($data);
        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function it_fails_when_required_fields_are_missing()
    {
        $data = [];

        $validator = $this->validate($data);
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('tanggal', $validator->errors()->toArray());
        $this->assertArrayHasKey('jenis', $validator->errors()->toArray());
        $this->assertArrayHasKey('kategori', $validator->errors()->toArray());
        $this->assertArrayHasKey('jumlah', $validator->errors()->toArray());
        $this->assertArrayHasKey('keterangan', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_when_tanggal_is_in_future()
    {
        $data = [
            'tanggal' => now()->addWeek()->format('Y-m-d'),
            'jenis' => 'penerimaan',
            'kategori' => 'Donasi Individu',
            'jumlah' => 100000,
            'keterangan' => 'Donasi dari Bapak Ahmad',
        ];

        $validator = $this->validate($data);
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('tanggal', $validator->errors()->toArray());
    }

    /** @test */
    public function it_passes_when_tanggal_is_in_past()
    {
        $data = [
            'tanggal' => now()->subDays(5)->format('Y-m-d'),
            'jenis' => 'penerimaan',
            'kategori' => 'Donasi Individu',
            'jumlah' => 100000,
            'keterangan' => 'Donasi dari Bapak Ahmad',
        ];

        $validator = $this->validate($data);
        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function it_fails_when_jenis_is_invalid()
    {
        $data = [
            'tanggal' => now()->format('Y-m-d'),
            'jenis' => 'transfer',
            'kategori' => 'Donasi Individu',
            'jumlah' => 100000,
            'keterangan' => 'Donasi dari Bapak Ahmad',
        ];

        $validator = $this->validate($data);
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('jenis', $validator->errors()->toArray());
    }

    /** @test */
    public function it_passes_with_both_valid_jenis_values()
    {
        $jenisValues = ['penerimaan', 'pengeluaran'];
        
        foreach ($jenisValues as $jenis) {
            $data = [
                'tanggal' => now()->format('Y-m-d'),
                'jenis' => $jenis,
                'kategori' => 'Test Kategori',
                'jumlah' => 100000,
                'keterangan' => 'Test keterangan',
            ];

            $validator = $this->validate($data);
            $this->assertTrue($validator->passes(), "Jenis {$jenis} should pass validation");
        }
    }

    /** @test */
    public function it_fails_when_kategori_exceeds_max_length()
    {
        $data = [
            'tanggal' => now()->format('Y-m-d'),
            'jenis' => 'penerimaan',
            'kategori' => str_repeat('x', 101),
            'jumlah' => 100000,
            'keterangan' => 'Donasi dari Bapak Ahmad',
        ];

        $validator = $this->validate($data);
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('kategori', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_when_jumlah_is_zero()
    {
        $data = [
            'tanggal' => now()->format('Y-m-d'),
            'jenis' => 'penerimaan',
            'kategori' => 'Donasi Individu',
            'jumlah' => 0,
            'keterangan' => 'Donasi dari Bapak Ahmad',
        ];

        $validator = $this->validate($data);
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('jumlah', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_when_jumlah_is_negative()
    {
        $data = [
            'tanggal' => now()->format('Y-m-d'),
            'jenis' => 'penerimaan',
            'kategori' => 'Donasi Individu',
            'jumlah' => -500,
            'keterangan' => 'Donasi dari Bapak Ahmad',
        ];

        $validator = $this->validate($data);
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('jumlah', $validator->errors()->toArray());
    }

    /** @test */
    public function it_passes_with_decimal_jumlah()
    {
        $data = [
            'tanggal' => now()->format('Y-m-d'),
            'jenis' => 'penerimaan',
            'kategori' => 'Donasi Individu',
            'jumlah' => 150000.50,
            'keterangan' => 'Donasi dari Bapak Ahmad',
        ];

        $validator = $this->validate($data);
        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function it_passes_with_minimum_valid_jumlah()
    {
        $data = [
            'tanggal' => now()->format('Y-m-d'),
            'jenis' => 'penerimaan',
            'kategori' => 'Donasi Individu',
            'jumlah' => 0.01,
            'keterangan' => 'Donasi dari Bapak Ahmad',
        ];

        $validator = $this->validate($data);
        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function it_fails_when_keterangan_exceeds_max_length()
    {
        $data = [
            'tanggal' => now()->format('Y-m-d'),
            'jenis' => 'penerimaan',
            'kategori' => 'Donasi Individu',
            'jumlah' => 100000,
            'keterangan' => str_repeat('a', 1001),
        ];

        $validator = $this->validate($data);
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('keterangan', $validator->errors()->toArray());
    }

    /** @test */
    public function it_passes_when_bukti_file_is_optional()
    {
        $data = [
            'tanggal' => now()->format('Y-m-d'),
            'jenis' => 'penerimaan',
            'kategori' => 'Donasi Individu',
            'jumlah' => 100000,
            'keterangan' => 'Donasi dari Bapak Ahmad',
        ];

        $validator = $this->validate($data);
        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function it_has_indonesian_error_messages()
    {
        $data = [];

        $validator = $this->validate($data);
        $errors = $validator->errors();
        
        $this->assertStringContainsString('harus diisi', $errors->first('tanggal'));
        $this->assertStringContainsString('harus dipilih', $errors->first('jenis'));
        $this->assertStringContainsString('harus diisi', $errors->first('kategori'));
        $this->assertStringContainsString('harus diisi', $errors->first('jumlah'));
        $this->assertStringContainsString('harus diisi', $errors->first('keterangan'));
    }

    /** @test */
    public function it_has_custom_message_for_invalid_jenis()
    {
        $data = [
            'tanggal' => now()->format('Y-m-d'),
            'jenis' => 'invalid',
            'kategori' => 'Donasi Individu',
            'jumlah' => 100000,
            'keterangan' => 'Donasi dari Bapak Ahmad',
        ];

        $validator = $this->validate($data);
        $errors = $validator->errors();
        
        $this->assertStringContainsString('penerimaan atau pengeluaran', $errors->first('jenis'));
    }

    /** @test */
    public function it_has_custom_message_for_jumlah_validation()
    {
        $data = [
            'tanggal' => now()->format('Y-m-d'),
            'jenis' => 'penerimaan',
            'kategori' => 'Donasi Individu',
            'jumlah' => -100,
            'keterangan' => 'Donasi dari Bapak Ahmad',
        ];

        $validator = $this->validate($data);
        $errors = $validator->errors();
        
        $this->assertStringContainsString('lebih dari 0', $errors->first('jumlah'));
    }

    /** @test */
    public function it_has_custom_messages_defined()
    {
        $request = new UpdateTransaksiRequest();
        $messages = $request->messages();

        $this->assertArrayHasKey('tanggal.required', $messages);
        $this->assertArrayHasKey('tanggal.before_or_equal', $messages);
        $this->assertArrayHasKey('jenis.required', $messages);
        $this->assertArrayHasKey('jenis.in', $messages);
        $this->assertArrayHasKey('kategori.required', $messages);
        $this->assertArrayHasKey('jumlah.required', $messages);
        $this->assertArrayHasKey('jumlah.min', $messages);
        $this->assertArrayHasKey('keterangan.required', $messages);
        $this->assertArrayHasKey('bukti_file.mimes', $messages);
        $this->assertArrayHasKey('bukti_file.max', $messages);
    }
}
