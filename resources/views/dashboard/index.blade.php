@extends('layouts.app')

@section('title', 'Dashboard - Sistem Data Anak Yatim')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Dashboard</h1>

    <!-- Financial Summary Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-cash-coin"></i> Ringkasan Keuangan</h5>
                    <a href="{{ route('keuangan.dashboard') }}" class="btn btn-sm btn-light">Lihat Detail</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center">
                                <i class="bi bi-wallet2 fs-1 text-primary"></i>
                                <h6 class="mt-2">Saldo Kas</h6>
                                <h4 class="text-primary">Rp {{ number_format($keuangan['saldo_kas'], 0, ',', '.') }}</h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <i class="bi bi-arrow-down-circle fs-1 text-success"></i>
                                <h6 class="mt-2">Penerimaan Bulan Ini</h6>
                                <h4 class="text-success">Rp {{ number_format($keuangan['total_penerimaan_bulan_ini'], 0, ',', '.') }}</h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <i class="bi bi-arrow-up-circle fs-1 text-danger"></i>
                                <h6 class="mt-2">Pengeluaran Bulan Ini</h6>
                                <h4 class="text-danger">Rp {{ number_format($keuangan['total_pengeluaran_bulan_ini'], 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Total Anak</h6>
                            <h2 class="mb-0">{{ $stats['total_anak'] }}</h2>
                        </div>
                        <i class="bi bi-people fs-1"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Laki-laki</h6>
                            <h2 class="mb-0">{{ $stats['by_gender']['Laki-laki'] }}</h2>
                        </div>
                        <i class="bi bi-gender-male fs-1"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Perempuan</h6>
                            <h2 class="mb-0">{{ $stats['by_gender']['Perempuan'] }}</h2>
                        </div>
                        <i class="bi bi-gender-female fs-1"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Jenjang Pendidikan</h6>
                            <h2 class="mb-0">{{ count($stats['by_pendidikan']) }}</h2>
                        </div>
                        <i class="bi bi-book fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Absensi Summary (admin & staff only) -->
    @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Absensi Bulan Ini</h5>
                    <a href="{{ route('absensi.approval') }}" class="btn btn-sm btn-dark">Kelola Absensi</a>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <a href="{{ route('absensi.approval') }}?status=pending" class="text-decoration-none">
                                <div class="p-3 rounded bg-warning bg-opacity-10">
                                    <h3 class="text-warning mb-0">{{ $absensi['pending'] }}</h3>
                                    <small class="text-muted">Menunggu Persetujuan</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('absensi.approval') }}?status=disetujui" class="text-decoration-none">
                                <div class="p-3 rounded bg-success bg-opacity-10">
                                    <h3 class="text-success mb-0">{{ $absensi['disetujui'] }}</h3>
                                    <small class="text-muted">Sudah Disetujui</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('absensi.tidak-hadir') }}" class="text-decoration-none">
                                <div class="p-3 rounded bg-danger bg-opacity-10">
                                    <h3 class="text-danger mb-0">{{ $absensi['tidak_hadir_3x'] }}</h3>
                                    <small class="text-muted">Tidak Hadir ≥ 3 Bulan</small>
                                    @if($absensi['tidak_hadir_3x'] > 0)
                                        <div><span class="badge bg-danger">Perlu Tindakan</span></div>
                                    @endif
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            @php
                $pendingOrangTua = \App\Models\User::where('role','orang_tua')->where('status_akun','pending')->count();
            @endphp
            <div class="card {{ $pendingOrangTua > 0 ? 'border-danger' : 'border-secondary' }} h-100">
                <div class="card-header {{ $pendingOrangTua > 0 ? 'bg-danger text-white' : 'bg-light' }} d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-person-check"></i> Pendaftaran Orang Tua</h5>
                    <a href="{{ route('users.approval-orang-tua') }}"
                       class="btn btn-sm {{ $pendingOrangTua > 0 ? 'btn-light' : 'btn-outline-secondary' }}">
                        Lihat
                    </a>
                </div>
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    @if($pendingOrangTua > 0)
                        <h2 class="text-danger mb-1">{{ $pendingOrangTua }}</h2>
                        <p class="text-muted mb-0">Menunggu persetujuan</p>
                        <div class="mt-2">
                            <span class="badge bg-danger">Perlu Tindakan</span>
                        </div>
                    @else
                        <i class="bi bi-check-circle-fill fs-1 text-success"></i>
                        <p class="text-muted mt-2 mb-0">Tidak ada pendaftaran baru</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Distribusi Kelompok Usia</h5>
                </div>
                <div class="card-body">
                    <canvas id="ageGroupChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Distribusi Pendidikan</h5>
                </div>
                <div class="card-body">
                    <canvas id="pendidikanChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Entries -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Anak yang Baru Masuk</h5>
                    <a href="{{ route('anak-yatim.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    @if($stats['recent_entries']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Usia</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Pendidikan</th>
                                        <th>Tanggal Masuk</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stats['recent_entries'] as $anak)
                                        <tr>
                                            <td>{{ $anak->nama_lengkap }}</td>
                                            <td>{{ $anak->usia }} tahun</td>
                                            <td>{{ $anak->jenis_kelamin }}</td>
                                            <td>{{ $anak->pendidikan_terakhir ?? '-' }}</td>
                                            <td>{{ $anak->tanggal_masuk->format('d/m/Y') }}</td>
                                            <td>
                                                <a href="{{ route('anak-yatim.show', $anak) }}" class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center mb-0">Belum ada data anak yatim</p>
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
                    <i class="bi bi-plus-circle fs-1 text-primary"></i>
                    <h5 class="mt-3">Tambah Data Anak</h5>
                    <p class="text-muted">Tambahkan data anak yatim baru</p>
                    <a href="{{ route('anak-yatim.create') }}" class="btn btn-primary">Tambah Data</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-list-ul fs-1 text-info"></i>
                    <h5 class="mt-3">Daftar Anak</h5>
                    <p class="text-muted">Lihat dan kelola data anak yatim</p>
                    <a href="{{ route('anak-yatim.index') }}" class="btn btn-info">Lihat Daftar</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-file-earmark-text fs-1 text-success"></i>
                    <h5 class="mt-3">Laporan</h5>
                    <p class="text-muted">Buat dan export laporan</p>
                    <a href="{{ route('laporan.index') }}" class="btn btn-success">Buat Laporan</a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Age Group Chart
    const ageGroupCtx = document.getElementById('ageGroupChart').getContext('2d');
    new Chart(ageGroupCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($stats['by_age_group'])) !!},
            datasets: [{
                label: 'Jumlah Anak',
                data: {!! json_encode(array_values($stats['by_age_group'])) !!},
                backgroundColor: [
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(255, 99, 132, 0.8)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Pendidikan Chart
    const pendidikanCtx = document.getElementById('pendidikanChart').getContext('2d');
    new Chart(pendidikanCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($stats['by_pendidikan'])) !!},
            datasets: [{
                label: 'Jumlah Anak',
                data: {!! json_encode(array_values($stats['by_pendidikan'])) !!},
                backgroundColor: [
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(153, 102, 255, 0.8)',
                    'rgba(255, 159, 64, 0.8)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true
        }
    });
</script>
@endpush
@endsection
