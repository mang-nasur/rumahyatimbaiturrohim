<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class DashboardKeuanganControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_keuangan_displays_successfully(): void
    {
        $user = User::factory()->create(['role' => 'bendahara']);
        
        $response = $this->actingAs($user)->get(route('keuangan.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('keuangan.dashboard');
        $response->assertViewHas('stats');
    }

    public function test_dashboard_displays_correct_statistics(): void
    {
        $user = User::factory()->create(['role' => 'bendahara']);
        
        // Create test transactions
        Transaksi::factory()->create([
            'jenis' => 'penerimaan',
            'jumlah' => 1000000,
            'tanggal' => Carbon::now()
        ]);

        Transaksi::factory()->create([
            'jenis' => 'pengeluaran',
            'jumlah' => 500000,
            'tanggal' => Carbon::now()
        ]);

        $response = $this->actingAs($user)->get(route('keuangan.dashboard'));

        $response->assertStatus(200);
        
        $stats = $response->viewData('stats');
        
        $this->assertEquals(500000, $stats['saldo_kas']);
        $this->assertEquals(1000000, $stats['total_penerimaan_bulan_ini']);
        $this->assertEquals(500000, $stats['total_pengeluaran_bulan_ini']);
    }

    public function test_dashboard_displays_chart_data(): void
    {
        $user = User::factory()->create(['role' => 'bendahara']);
        
        $response = $this->actingAs($user)->get(route('keuangan.dashboard'));

        $response->assertStatus(200);
        
        $stats = $response->viewData('stats');
        
        $this->assertArrayHasKey('grafik_data', $stats);
        $this->assertArrayHasKey('labels', $stats['grafik_data']);
        $this->assertArrayHasKey('penerimaan', $stats['grafik_data']);
        $this->assertArrayHasKey('pengeluaran', $stats['grafik_data']);
        $this->assertCount(6, $stats['grafik_data']['labels']);
    }

    public function test_dashboard_displays_recent_transactions(): void
    {
        $user = User::factory()->create(['role' => 'bendahara']);
        
        // Create 15 transactions
        Transaksi::factory()->count(15)->create();

        $response = $this->actingAs($user)->get(route('keuangan.dashboard'));

        $response->assertStatus(200);
        
        $stats = $response->viewData('stats');
        
        $this->assertArrayHasKey('transaksi_terbaru', $stats);
        $this->assertCount(10, $stats['transaksi_terbaru']);
    }

    public function test_dashboard_shows_empty_state_when_no_transactions(): void
    {
        $user = User::factory()->create(['role' => 'bendahara']);
        
        $response = $this->actingAs($user)->get(route('keuangan.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Belum ada transaksi');
    }
}
