<?php

namespace Database\Seeders;

use App\Models\AnakYatim;
use Illuminate\Database\Seeder;

class AnakYatimSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 dummy records with varied data
        AnakYatim::factory()->count(10)->create();

        // You can also create specific records if needed
        // AnakYatim::create([
        //     'nama_lengkap' => 'Ahmad Fauzi',
        //     'tempat_lahir' => 'Jakarta',
        //     'tanggal_lahir' => '2010-05-15',
        //     'jenis_kelamin' => 'Laki-laki',
        //     'alamat' => 'Jl. Merdeka No. 123, Jakarta Selatan',
        //     'nama_ayah' => 'Budi Santoso',
        //     'status_ayah' => 'Meninggal',
        //     'nama_ibu' => 'Siti Aminah',
        //     'status_ibu' => 'Hidup',
        //     'nomor_telepon_wali' => '+62812345678',
        //     'tanggal_masuk' => '2020-01-10',
        //     'pendidikan_terakhir' => 'SD',
        //     'sekolah_saat_ini' => 'SDN 01 Jakarta',
        // ]);
    }
}
