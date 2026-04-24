<?php

namespace Database\Factories;

use App\Models\Transaksi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaksi>
 */
class TransaksiFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaksi::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jenis = fake()->randomElement([Transaksi::JENIS_PENERIMAAN, Transaksi::JENIS_PENGELUARAN]);
        
        $kategori = $jenis === Transaksi::JENIS_PENERIMAAN
            ? fake()->randomElement(Transaksi::KATEGORI_PENERIMAAN)
            : fake()->randomElement(Transaksi::KATEGORI_PENGELUARAN);

        return [
            'tanggal' => fake()->dateTimeBetween('-1 year', 'now'),
            'jenis' => $jenis,
            'kategori' => $kategori,
            'jumlah' => fake()->randomFloat(2, 10000, 10000000),
            'keterangan' => fake()->sentence(10),
            'bukti_file' => null,
        ];
    }

    /**
     * Indicate that the transaction is a receipt (penerimaan).
     *
     * @return static
     */
    public function penerimaan()
    {
        return $this->state(function (array $attributes) {
            return [
                'jenis' => Transaksi::JENIS_PENERIMAAN,
                'kategori' => fake()->randomElement(Transaksi::KATEGORI_PENERIMAAN),
            ];
        });
    }

    /**
     * Indicate that the transaction is an expense (pengeluaran).
     *
     * @return static
     */
    public function pengeluaran()
    {
        return $this->state(function (array $attributes) {
            return [
                'jenis' => Transaksi::JENIS_PENGELUARAN,
                'kategori' => fake()->randomElement(Transaksi::KATEGORI_PENGELUARAN),
            ];
        });
    }

    /**
     * Indicate that the transaction has a proof file.
     *
     * @return static
     */
    public function withBuktiFile()
    {
        return $this->state(function (array $attributes) {
            return [
                'bukti_file' => 'bukti/' . fake()->uuid() . '.pdf',
            ];
        });
    }
}
