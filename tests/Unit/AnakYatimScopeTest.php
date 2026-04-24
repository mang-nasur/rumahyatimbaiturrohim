<?php

namespace Tests\Unit;

use App\Models\AnakYatim;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnakYatimScopeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test scopeSearch filters by nama_lengkap, nama_ayah, and nama_ibu.
     */
    public function test_scope_search_filters_by_names(): void
    {
        // Create test data
        AnakYatim::create([
            'nama_lengkap' => 'Ahmad Fauzi',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2010-05-15',
            'jenis_kelamin' => 'Laki-laki',
            'nama_ayah' => 'Budi Santoso',
            'nama_ibu' => 'Siti Aminah',
            'tanggal_masuk' => '2020-01-10',
        ]);

        AnakYatim::create([
            'nama_lengkap' => 'Fatimah Zahra',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '2012-03-20',
            'jenis_kelamin' => 'Perempuan',
            'nama_ayah' => 'Ali Rahman',
            'nama_ibu' => 'Khadijah',
            'tanggal_masuk' => '2021-02-15',
        ]);

        // Test search by child name
        $results = AnakYatim::search('Ahmad')->get();
        $this->assertCount(1, $results);
        $this->assertEquals('Ahmad Fauzi', $results->first()->nama_lengkap);

        // Test search by father name
        $results = AnakYatim::search('Budi')->get();
        $this->assertCount(1, $results);
        $this->assertEquals('Ahmad Fauzi', $results->first()->nama_lengkap);

        // Test search by mother name
        $results = AnakYatim::search('Khadijah')->get();
        $this->assertCount(1, $results);
        $this->assertEquals('Fatimah Zahra', $results->first()->nama_lengkap);
    }

    /**
     * Test scopeByAgeRange filters by age range correctly.
     */
    public function test_scope_by_age_range_filters_correctly(): void
    {
        // Create children with different ages
        AnakYatim::create([
            'nama_lengkap' => 'Child 5 years',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => now()->subYears(5)->format('Y-m-d'),
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_masuk' => '2020-01-10',
        ]);

        AnakYatim::create([
            'nama_lengkap' => 'Child 10 years',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => now()->subYears(10)->format('Y-m-d'),
            'jenis_kelamin' => 'Perempuan',
            'tanggal_masuk' => '2020-01-10',
        ]);

        AnakYatim::create([
            'nama_lengkap' => 'Child 15 years',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => now()->subYears(15)->format('Y-m-d'),
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_masuk' => '2020-01-10',
        ]);

        // Test age range 6-12
        $results = AnakYatim::byAgeRange(6, 12)->get();
        $this->assertCount(1, $results);
        $this->assertEquals('Child 10 years', $results->first()->nama_lengkap);

        // Test age range 0-10
        $results = AnakYatim::byAgeRange(0, 10)->get();
        $this->assertCount(2, $results);
    }

    /**
     * Test scopeByPendidikan filters by education level.
     */
    public function test_scope_by_pendidikan_filters_correctly(): void
    {
        AnakYatim::create([
            'nama_lengkap' => 'SD Student',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2010-05-15',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_masuk' => '2020-01-10',
            'pendidikan_terakhir' => 'SD',
        ]);

        AnakYatim::create([
            'nama_lengkap' => 'SMP Student',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2008-05-15',
            'jenis_kelamin' => 'Perempuan',
            'tanggal_masuk' => '2020-01-10',
            'pendidikan_terakhir' => 'SMP',
        ]);

        $results = AnakYatim::byPendidikan('SD')->get();
        $this->assertCount(1, $results);
        $this->assertEquals('SD Student', $results->first()->nama_lengkap);
    }

    /**
     * Test scopeByTahunMasuk filters by entry year.
     */
    public function test_scope_by_tahun_masuk_filters_correctly(): void
    {
        AnakYatim::create([
            'nama_lengkap' => 'Entered 2020',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2010-05-15',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_masuk' => '2020-06-15',
        ]);

        AnakYatim::create([
            'nama_lengkap' => 'Entered 2021',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2010-05-15',
            'jenis_kelamin' => 'Perempuan',
            'tanggal_masuk' => '2021-03-20',
        ]);

        $results = AnakYatim::byTahunMasuk(2020)->get();
        $this->assertCount(1, $results);
        $this->assertEquals('Entered 2020', $results->first()->nama_lengkap);
    }

    /**
     * Test combining multiple scopes together.
     */
    public function test_combining_multiple_scopes(): void
    {
        AnakYatim::create([
            'nama_lengkap' => 'Ahmad Fauzi',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => now()->subYears(10)->format('Y-m-d'),
            'jenis_kelamin' => 'Laki-laki',
            'nama_ayah' => 'Budi',
            'tanggal_masuk' => '2020-01-10',
            'pendidikan_terakhir' => 'SD',
        ]);

        AnakYatim::create([
            'nama_lengkap' => 'Fatimah',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => now()->subYears(10)->format('Y-m-d'),
            'jenis_kelamin' => 'Perempuan',
            'nama_ayah' => 'Ali',
            'tanggal_masuk' => '2021-01-10',
            'pendidikan_terakhir' => 'SD',
        ]);

        // Combine search, age range, education, and year
        $results = AnakYatim::search('Ahmad')
            ->byAgeRange(8, 12)
            ->byPendidikan('SD')
            ->byTahunMasuk(2020)
            ->get();

        $this->assertCount(1, $results);
        $this->assertEquals('Ahmad Fauzi', $results->first()->nama_lengkap);
    }
}
