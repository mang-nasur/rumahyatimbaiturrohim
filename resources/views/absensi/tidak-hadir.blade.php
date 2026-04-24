@extends('layouts.app')

@section('title', 'Anak Yatim Tidak Hadir')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="bi bi-exclamation-triangle-fill text-warning"></i> Anak Yatim Tidak Hadir</h2>
        <p class="text-muted mb-0">Daftar anak yang tidak hadir 3 bulan berturut-turut atau lebih</p>
    </div>
    <a href="{{ route('absensi.approval') }}" class="btn btn-outline-primary">
        <i class="bi bi-clipboard2-check"></i> Halaman Approval
    </a>
</div>

{{-- Ringkasan --}}
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-warning">
            <div class="card-body text-center py-3">
                <i class="bi bi-1-circle fs-2 text-warning"></i>
                <h3 class="mb-0 mt-1">{{ $tidakHadir1Bulan }}</h3>
                <small class="text-muted">Tidak hadir 1 bulan</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-orange" style="border-color: #fd7e14 !important;">
            <div class="card-body text-center py-3">
                <i class="bi bi-2-circle fs-2" style="color:#fd7e14;"></i>
                <h3 class="mb-0 mt-1">{{ $tidakHadir2Bulan }}</h3>
                <small class="text-muted">Tidak hadir 2 bulan</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-danger">
            <div class="card-body text-center py-3">
                <i class="bi bi-exclamation-circle-fill fs-2 text-danger"></i>
                <h3 class="mb-0 mt-1 text-danger">{{ $tidakHadir3Plus }}</h3>
                <small class="text-muted">Tidak hadir ≥ 3 bulan <span class="badge bg-danger">Perlu Tindakan</span></small>
            </div>
        </div>
    </div>
</div>

{{-- Tabel Anak Tidak Hadir ≥ 3 Bulan --}}
<div class="card">
    <div class="card-header bg-danger text-white">
        <h5 class="mb-0">
            <i class="bi bi-person-x-fill"></i>
            Anak Tidak Hadir ≥ 3 Bulan Berturut-turut
            <span class="badge bg-light text-danger ms-2">{{ $tidakHadirList->count() }} anak</span>
        </h5>
    </div>
    <div class="card-body p-0">
        @if($tidakHadirList->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-check-circle-fill fs-1 text-success"></i>
                <p class="mt-2 mb-0 fw-semibold text-success">Semua anak hadir dengan baik!</p>
                <p class="text-muted small">Tidak ada anak yang tidak hadir 3 bulan berturut-turut.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Anak</th>
                            <th>Pendidikan</th>
                            <th class="text-center">Tidak Hadir</th>
                            <th>Absensi Terakhir Disetujui</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tidakHadirList as $anak)
                        @php
                            $absensiTerakhir = $anak->absensi->first(); // sudah difilter disetujui & sorted
                            $jumlah = $anak->jumlah_tidak_hadir;
                        @endphp
                        <tr class="{{ $jumlah >= 6 ? 'table-danger' : ($jumlah >= 3 ? 'table-warning' : '') }}">
                            <td>
                                <a href="{{ route('anak-yatim.show', $anak) }}"
                                   class="fw-semibold text-decoration-none">
                                    {{ $anak->nama_lengkap }}
                                </a>
                                <div class="text-muted small">{{ $anak->usia }} tahun</div>
                            </td>
                            <td>{{ $anak->pendidikan_terakhir ?? '-' }}</td>
                            <td class="text-center">
                                <span class="badge fs-6 {{ $jumlah >= 6 ? 'bg-danger' : 'bg-warning text-dark' }}">
                                    {{ $jumlah }} bulan
                                </span>
                            </td>
                            <td>
                                @if($absensiTerakhir)
                                    @php
                                        $namaBulan = [1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'Mei',6=>'Jun',
                                                      7=>'Jul',8=>'Agu',9=>'Sep',10=>'Okt',11=>'Nov',12=>'Des'];
                                    @endphp
                                    {{ $namaBulan[$absensiTerakhir->bulan] }} {{ $absensiTerakhir->tahun }}
                                @else
                                    <span class="text-muted">Belum pernah hadir</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('absensi.riwayat', $anak) }}"
                                   class="btn btn-sm btn-outline-primary" title="Lihat Riwayat">
                                    <i class="bi bi-clock-history"></i> Riwayat
                                </a>
                                <a href="{{ route('anak-yatim.show', $anak) }}"
                                   class="btn btn-sm btn-outline-secondary" title="Detail Anak">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<div class="mt-3 text-muted small">
    <i class="bi bi-info-circle"></i>
    Perhitungan berdasarkan absensi yang sudah <strong>disetujui</strong> oleh staff.
    Baris <span class="badge bg-warning text-dark">kuning</span> = 3–5 bulan tidak hadir,
    <span class="badge bg-danger">merah</span> = 6 bulan atau lebih.
</div>

{{-- Tabel Anak yang Diwakilkan --}}
<div class="card mt-4">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0">
            <i class="bi bi-person-heart"></i>
            Anak yang Diwakilkan Ibu / Wali
            <span class="badge bg-light text-info ms-2">{{ $diwakilkanList->count() }} anak</span>
        </h5>
    </div>
    <div class="card-body p-0">
        @if($diwakilkanList->isEmpty())
            <div class="text-center py-4 text-muted">
                <i class="bi bi-check-circle-fill fs-1 text-success"></i>
                <p class="mt-2 mb-0">Tidak ada anak yang pernah diwakilkan.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Anak</th>
                            <th>Pendidikan</th>
                            <th class="text-center">Total Diwakilkan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($diwakilkanList as $anak)
                        <tr>
                            <td>
                                <a href="{{ route('anak-yatim.show', $anak) }}"
                                   class="fw-semibold text-decoration-none">
                                    {{ $anak->nama_lengkap }}
                                </a>
                                <div class="text-muted small">{{ $anak->usia }} tahun</div>
                            </td>
                            <td>{{ $anak->pendidikan_terakhir ?? '-' }}</td>
                            <td class="text-center">
                                <span class="badge fs-6 {{ $anak->total_diwakilkan >= 3 ? 'bg-warning text-dark' : 'bg-info' }}">
                                    {{ $anak->total_diwakilkan }}x diwakili
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('absensi.riwayat', $anak) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-clock-history"></i> Riwayat
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
<div class="mt-2 text-muted small">
    <i class="bi bi-info-circle"></i>
    Dihitung dari total absensi yang disetujui dengan kehadiran <strong>Ibu / Wali</strong> (bukan anak langsung).
</div>
@endsection
