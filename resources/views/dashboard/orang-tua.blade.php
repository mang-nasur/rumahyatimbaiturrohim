@extends('layouts.app')

@section('title', 'Dashboard - Orang Tua')

@section('content')
<div class="container mt-4">
    <h1 class="mb-1">Selamat Datang, {{ auth()->user()->name }}</h1>
    <p class="text-muted mb-4">Portal Orang Tua / Wali — Rumah Yatim Baiturrohim</p>

    @if(!$anak)
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle-fill"></i>
            Akun Anda belum terhubung ke data anak yatim. Silakan hubungi pengurus.
        </div>
    @else

    {{-- Info Anak --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-primary h-100">
                <div class="card-body text-center py-4">
                    @if($anak->foto)
                        <img src="{{ asset('storage/' . $anak->foto) }}"
                             alt="Foto {{ $anak->nama_lengkap }}"
                             class="rounded-circle mb-3"
                             style="width:100px;height:100px;object-fit:cover;">
                    @else
                        <i class="bi bi-person-circle text-primary mb-3" style="font-size:5rem;"></i>
                    @endif
                    <h5 class="mb-1">{{ $anak->nama_lengkap }}</h5>
                    <p class="text-muted mb-2">{{ $anak->usia }} tahun &bull; {{ $anak->pendidikan_terakhir ?? '-' }}</p>
                    <span class="badge {{ $anak->is_aktif ? 'bg-success' : 'bg-secondary' }}">
                        {{ $anak->is_aktif ? 'Aktif' : 'Tidak Aktif' }}
                    </span>
                    <div class="mt-3 d-grid gap-2">
                        <a href="{{ route('anak-yatim.show', $anak) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-eye"></i> Lihat Data Lengkap
                        </a>
                        <a href="{{ route('anak-yatim.edit', $anak) }}" class="btn btn-outline-warning btn-sm">
                            <i class="bi bi-pencil"></i> Ubah Data Diri
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            {{-- Status Absensi Bulan Ini --}}
            @php
                $namaBulan = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
                              7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
                $bulanIni = (int) now()->format('n');
                $tahunIni = (int) now()->format('Y');
            @endphp
            <div class="card mb-3 {{ $absensibulanIni ? ($absensibulanIni->status === 'disetujui' ? 'border-success' : ($absensibulanIni->status === 'ditolak' ? 'border-danger' : 'border-warning')) : 'border-secondary' }}">
                <div class="card-header d-flex justify-content-between align-items-center
                    {{ $absensibulanIni ? ($absensibulanIni->status === 'disetujui' ? 'bg-success text-white' : ($absensibulanIni->status === 'ditolak' ? 'bg-danger text-white' : 'bg-warning text-dark')) : 'bg-light' }}">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-check"></i>
                        Absensi {{ $namaBulan[$bulanIni] }} {{ $tahunIni }}
                    </h5>
                    @if(!$absensibulanIni)
                        <a href="{{ route('absensi.create') }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-circle"></i> Isi Absensi
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    @if(!$absensibulanIni)
                        <div class="text-center py-3">
                            <i class="bi bi-calendar-x fs-1 text-muted"></i>
                            <p class="mt-2 mb-3 text-muted">Belum mengisi absensi bulan ini</p>
                            <a href="{{ route('absensi.create') }}" class="btn btn-primary">
                                <i class="bi bi-pencil-square"></i> Isi Absensi Sekarang
                            </a>
                        </div>
                    @elseif($absensibulanIni->status === 'disetujui')
                        <div class="text-center py-2">
                            <i class="bi bi-check-circle-fill fs-1 text-success"></i>
                            <p class="mt-2 mb-0 fw-semibold text-success">Absensi Disetujui</p>
                            <p class="text-muted small">
                                Hadir sebagai: <strong>{{ $absensibulanIni->hadir_sebagai === 'anak' ? 'Anak' : 'Ibu / Wali' }}</strong>
                                &bull; Disetujui: {{ $absensibulanIni->approved_at?->format('d/m/Y') }}
                            </p>
                        </div>
                    @elseif($absensibulanIni->status === 'pending')
                        <div class="text-center py-2">
                            <i class="bi bi-hourglass-split fs-1 text-warning"></i>
                            <p class="mt-2 mb-0 fw-semibold text-warning">Menunggu Persetujuan</p>
                            <p class="text-muted small">
                                Disubmit: {{ $absensibulanIni->submitted_at?->format('d/m/Y H:i') }}
                                &bull; Hadir sebagai: <strong>{{ $absensibulanIni->hadir_sebagai === 'anak' ? 'Anak' : 'Ibu / Wali' }}</strong>
                            </p>
                        </div>
                    @elseif($absensibulanIni->status === 'ditolak')
                        <div class="text-center py-2">
                            <i class="bi bi-x-circle-fill fs-1 text-danger"></i>
                            <p class="mt-2 mb-1 fw-semibold text-danger">Absensi Ditolak</p>
                            @if($absensibulanIni->catatan_staff)
                                <p class="text-muted small">Alasan: {{ $absensibulanIni->catatan_staff }}</p>
                            @endif
                            <a href="{{ route('absensi.create') }}" class="btn btn-sm btn-primary mt-1">
                                <i class="bi bi-arrow-repeat"></i> Submit Ulang
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Info Singkat Anak --}}
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-info-circle"></i> Info Singkat</h6>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-6">
                            <small class="text-muted d-block">Tanggal Masuk</small>
                            <span class="fw-semibold">{{ $anak->tanggal_masuk->format('d/m/Y') }}</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Kelas Saat Masuk</small>
                            <span class="fw-semibold">{{ $anak->kelas_saat_masuk ?? '-' }}</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Estimasi Keluar</small>
                            <span class="fw-semibold">
                                {{ $anak->estimasi_keluar ? $anak->estimasi_keluar->format('M Y') : '-' }}
                            </span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Sekolah Saat Ini</small>
                            <span class="fw-semibold">{{ $anak->sekolah_saat_ini ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Riwayat Absensi --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-clock-history"></i> Riwayat Absensi</h5>
            <a href="{{ route('absensi.riwayat', $anak) }}" class="btn btn-sm btn-outline-primary">
                Lihat Semua
            </a>
        </div>
        <div class="card-body p-0">
            @if($riwayatAbsensi->isEmpty())
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-inbox"></i> Belum ada riwayat absensi
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Periode</th>
                                <th>Yang Hadir</th>
                                <th class="text-center">Status</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($riwayatAbsensi as $absensi)
                            <tr>
                                <td class="fw-semibold">{{ $absensi->nama_bulan }} {{ $absensi->tahun }}</td>
                                <td>
                                    @if($absensi->hadir_sebagai === 'anak')
                                        <i class="bi bi-person-fill text-primary"></i> Anak
                                    @elseif($absensi->hadir_sebagai === 'ibu')
                                        <i class="bi bi-person-heart text-danger"></i> Ibu / Wali
                                    @else -
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $absensi->badge_status }}">
                                        {{ $absensi->label_status }}
                                    </span>
                                </td>
                                <td class="text-muted small">{{ $absensi->catatan_staff ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    @endif {{-- end if $anak --}}
</div>
@endsection
