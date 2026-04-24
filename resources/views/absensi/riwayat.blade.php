@extends('layouts.app')

@section('title', 'Riwayat Absensi - ' . $anakYatim->nama_lengkap)

@section('content')
<div class="mb-4">
    <h2><i class="bi bi-clock-history"></i> Riwayat Absensi</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('anak-yatim.show', $anakYatim) }}">{{ $anakYatim->nama_lengkap }}</a></li>
            <li class="breadcrumb-item active">Riwayat Absensi</li>
        </ol>
    </nav>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-primary">
            <div class="card-body text-center">
                <i class="bi bi-person-circle fs-1 text-primary"></i>
                <h5 class="mt-2 mb-1">{{ $anakYatim->nama_lengkap }}</h5>
                <span class="badge {{ $anakYatim->is_aktif ? 'bg-success' : 'bg-secondary' }}">
                    {{ $anakYatim->is_aktif ? 'Aktif' : 'Tidak Aktif' }}
                </span>
                <div class="mt-3">
                    <a href="{{ route('absensi.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle"></i> Submit Absensi Baru
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-list-check"></i> Daftar Absensi</h5>
            </div>
            <div class="card-body p-0">
                @if($absensiList->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1"></i>
                        <p class="mt-2">Belum ada riwayat absensi</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Periode</th>
                                    <th>Yang Hadir</th>
                                    <th>Waktu Submit</th>
                                    <th class="text-center">Status</th>
                                    <th>Catatan Staff</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($absensiList as $absensi)
                                <tr>
                                    <td class="fw-semibold">{{ $absensi->nama_bulan }} {{ $absensi->tahun }}</td>
                                    <td>
                                        @if($absensi->hadir_sebagai === 'anak')
                                            <i class="bi bi-person-fill text-primary"></i> Anak
                                        @elseif($absensi->hadir_sebagai === 'ibu')
                                            <i class="bi bi-person-heart text-danger"></i> Ibu / Wali
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $absensi->submitted_at ? $absensi->submitted_at->format('d/m/Y H:i') : '-' }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $absensi->badge_status }}">
                                            {{ $absensi->label_status }}
                                        </span>
                                    </td>
                                    <td>{{ $absensi->catatan_staff ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3">
                        {{ $absensiList->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
