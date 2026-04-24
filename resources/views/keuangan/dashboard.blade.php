@extends('layouts.app')

@section('title', 'Dashboard Keuangan - Sistem Manajemen Keuangan')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Dashboard Keuangan</h1>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Saldo Kas</h6>
                            <h2 class="mb-0">Rp {{ number_format($stats['saldo_kas'], 0, ',', '.') }}</h2>
                        </div>
                        <i class="bi bi-wallet2 fs-1"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Penerimaan Bulan Ini</h6>
                            <h2 class="mb-0">Rp {{ number_format($stats['total_penerimaan_bulan_ini'], 0, ',', '.') }}</h2>
                        </div>
                        <i class="bi bi-arrow-down-circle fs-1"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Pengeluaran Bulan Ini</h6>
                            <h2 class="mb-0">Rp {{ number_format($stats['total_pengeluaran_bulan_ini'], 0, ',', '.') }}</h2>
                        </div>
                        <i class="bi bi-arrow-up-circle fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Row -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Grafik Penerimaan vs Pengeluaran (6 Bulan Terakhir)</h5>
                </div>
                <div class="card-body">
                    <canvas id="penerimaanPengeluaranChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Transaksi Terbaru</h5>
                    <a href="{{ route('transaksi.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    @if($stats['transaksi_terbaru']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Jenis</th>
                                        <th>Kategori</th>
                                        <th>Keterangan</th>
                                        <th class="text-end">Jumlah</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stats['transaksi_terbaru'] as $transaksi)
                                        <tr>
                                            <td>{{ $transaksi->tanggal->format('d/m/Y') }}</td>
                                            <td>
                                                @if($transaksi->jenis === 'penerimaan')
                                                    <span class="badge bg-success">Penerimaan</span>
                                                @else
                                                    <span class="badge bg-danger">Pengeluaran</span>
                                                @endif
                                            </td>
                                            <td>{{ $transaksi->kategori }}</td>
                                            <td>{{ Str::limit($transaksi->keterangan, 50) }}</td>
                                            <td class="text-end">
                                                @if($transaksi->jenis === 'penerimaan')
                                                    <span class="text-success">+ Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}</span>
                                                @else
                                                    <span class="text-danger">- Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('transaksi.show', $transaksi) }}" class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center mb-0">Belum ada transaksi</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-plus-circle fs-1 text-success"></i>
                    <h5 class="mt-3">Tambah Penerimaan</h5>
                    <p class="text-muted">Catat transaksi penerimaan kas</p>
                    <a href="{{ route('transaksi.create') }}" class="btn btn-success">Tambah Transaksi</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-list-ul fs-1 text-info"></i>
                    <h5 class="mt-3">Daftar Transaksi</h5>
                    <p class="text-muted">Lihat dan kelola transaksi</p>
                    <a href="{{ route('transaksi.index') }}" class="btn btn-info">Lihat Daftar</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-file-earmark-text fs-1 text-primary"></i>
                    <h5 class="mt-3">Laporan Keuangan</h5>
                    <p class="text-muted">Buat dan export laporan</p>
                    <a href="{{ route('laporan.index') }}" class="btn btn-primary">Buat Laporan</a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Penerimaan vs Pengeluaran Chart
    const ctx = document.getElementById('penerimaanPengeluaranChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($stats['grafik_data']['labels']) !!},
            datasets: [
                {
                    label: 'Penerimaan',
                    data: {!! json_encode($stats['grafik_data']['penerimaan']) !!},
                    backgroundColor: 'rgba(25, 135, 84, 0.8)',
                    borderColor: 'rgba(25, 135, 84, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Pengeluaran',
                    data: {!! json_encode($stats['grafik_data']['pengeluaran']) !!},
                    backgroundColor: 'rgba(220, 53, 69, 0.8)',
                    borderColor: 'rgba(220, 53, 69, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            return label;
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection
