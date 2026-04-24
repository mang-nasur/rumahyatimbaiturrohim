<?php

namespace Database\Factories;

use App\Models\AnakYatim;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AnakYatim>
 */
class AnakYatimFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AnakYatim::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tanggalLahir = fake()->dateTimeBetween('-18 years', '-1 year');
        $tanggalMasuk = fake()->dateTimeBetween($tanggalLahir, 'now');

        return [
            'nama_lengkap' => fake()->name(),
            'tempat_lahir' => fake()->city(),
            'tanggal_lahir' => $tanggalLahir,
            'jenis_kelamin' => fake()->randomElement(['Laki-laki', 'Perempuan']),
            'alamat' => fake()->address(),
            'nama_ayah' => fake()->name('male'),
            'status_ayah' => fake()->randomElement(['Meninggal', 'Tidak Diketahui']),
            'nama_ibu' => fake()->name('female'),
            'status_ibu' => fake()->randomElement(['Meninggal', 'Tidak Diketahui', 'Hidup']),
            'nomor_telepon_wali' => fake()->numerify('+62##########'),
            'tanggal_masuk' => $tanggalMasuk,
            'pendidikan_terakhir' => fake()->randomElement(['TK', 'SD', 'SMP', 'SMA', 'Belum Sekolah']),
            'sekolah_saat_ini' => fake()->optional()->company() . ' School',
            'foto' => null,
        ];
    }
}
