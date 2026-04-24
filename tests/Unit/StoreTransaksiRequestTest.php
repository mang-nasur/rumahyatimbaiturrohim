<?php

namespace Tests\Unit;

use App\Http\Requests\StoreTransaksiRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class StoreTransaksiRequestTest extends TestCase
{
    private function validate(array $data): \Illuminate\Validation\Validator
    {
        $request = new StoreTransaksiRequest();
        return Validator::make($data, $request->rules(), $request->messages());
    }

    /** @test */
    public function it_passes_with_valid_required_fields()
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
    public function it_fails_when_tanggal_is_missing()
    {
        $data = [
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
    public function it_fails_when_tanggal_is_in_future()
    {
        $data = [
            'tanggal' => now()->addDay()->format('Y-m-d'),
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
    public function it_passes_when_tanggal_is_today()
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
    public function it_fails_when_jenis_is_missing()
    {
        $data = [
            'tanggal' => now()->format('Y-m-d'),
            'kategori' => 'Donasi Individu',
            'jumlah' => 100000,
            'keterangan' => 'Donasi dari Bapak Ahmad',
        ];

        $validator = $this->validate($data);
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('jenis', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_when_jenis_is_invalid()
    {
        $data = [
            'tanggal' => now()->format('Y-m-d'),
            'jenis' => 'invalid',
            'kategori' => 'Donasi Individu',
            'jumlah' => 100000,
            'keterangan' => 'Donasi dari Bapak Ahmad',
        ];

        $validator = $this->validate($data);
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('jenis', $validator->errors()->toArray());
    }

    /** @test */
    public function it_passes_with_valid_jenis_penerimaan()
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
    public function it_passes_with_valid_jenis_pengeluaran()
    {
        $data = [
            'tanggal' => now()->format('Y-m-d'),
            'jenis' => 'pengeluaran',
            'kategori' => 'Kebutuhan Anak',
            'jumlah' => 50000,
            'keterangan' => 'Pembelian buku',
        ];

        $validator = $this->validate($data);
        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function it_fails_when_kategori_is_missing()
    {
        $data = [
            'tanggal' => now()->format('Y-m-d'),
            'jenis' => 'penerimaan',
            'jumlah' => 100000,
            'keterangan' => 'Donasi dari Bapak Ahmad',
        ];

        $validator = $this->validate($data);
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('kategori', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_when_kategori_exceeds_max_length()
    {
        $data = [
            'tanggal' => now()->format('Y-m-d'),
            'jenis' => 'penerimaan',
            'kategori' => str_repeat('a', 101),
            'jumlah' => 100000,
            'keterangan' => 'Donasi dari Bapak Ahmad',
        ];

        $validator = $this->validate($data);
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('kategori', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_when_jumlah_is_missing()
    {
        $data = [
            'tanggal' => now()->format('Y-m-d'),
            'jenis' => 'penerimaan',
            'kategori' => 'Donasi Individu',
            'keterangan' => 'Donasi dari Bapak Ahmad',
        ];

        $validator = $this->validate($data);
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('jumlah', $validator->errors()->toArray());
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
            'jumlah' => -100,
            'keterangan' => 'Donasi dari Bapak Ahmad',
        ];

        $validator = $this->validate($data);
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('jumlah', $validator->errors()->toArray());
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
    public function it_fails_when_jumlah_is_not_numeric()
    {
        $data = [
            'tanggal' => now()->format('Y-m-d'),
            'jenis' => 'penerimaan',
            'kategori' => 'Donasi Individu',
            'jumlah' => 'not a number',
            'keterangan' => 'Donasi dari Bapak Ahmad',
        ];

        $validator = $this->validate($data);
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('jumlah', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_when_keterangan_is_missing()
    {
        $data = [
            'tanggal' => now()->format('Y-m-d'),
            'jenis' => 'penerimaan',
            'kategori' => 'Donasi Individu',
            'jumlah' => 100000,
        ];

        $validator = $this->validate($data);
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('keterangan', $validator->errors()->toArray());
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
    public function it_passes_when_bukti_file_is_not_provided()
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
        $data = [
            'jenis' => 'penerimaan',
        ];

        $validator = $this->validate($data);
        $errors = $validator->errors();
        
        $this->assertStringContainsString('harus diisi', $errors->first('tanggal'));
        $this->assertStringContainsString('harus diisi', $errors->first('kategori'));
        $this->assertStringContainsString('harus diisi', $errors->first('jumlah'));
        $this->assertStringContainsString('harus diisi', $errors->first('keterangan'));
    }

    /** @test */
    public function it_has_custom_message_for_jumlah_min()
    {
        $data = [
            'tanggal' => now()->format('Y-m-d'),
            'jenis' => 'penerimaan',
            'kategori' => 'Donasi Individu',
            'jumlah' => 0,
            'keterangan' => 'Donasi dari Bapak Ahmad',
        ];

        $validator = $this->validate($data);
        $errors = $validator->errors();
        
        $this->assertStringContainsString('lebih dari 0', $errors->first('jumlah'));
    }

    /** @test */
    public function it_has_custom_message_for_tanggal_future()
    {
        $data = [
            'tanggal' => now()->addDay()->format('Y-m-d'),
            'jenis' => 'penerimaan',
            'kategori' => 'Donasi Individu',
            'jumlah' => 100000,
            'keterangan' => 'Donasi dari Bapak Ahmad',
        ];

        $validator = $this->validate($data);
        $errors = $validator->errors();
        
        $this->assertStringContainsString('tidak boleh lebih dari hari ini', $errors->first('tanggal'));
    }
}
