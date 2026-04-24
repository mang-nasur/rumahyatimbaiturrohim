@extends('layouts.app')

@section('title', 'Preview Laporan - Sistem Data Anak Yatim')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Preview Laporan</h4>
            <div>
                <form method="POST" action="{{ route('laporan.pdf') }}" class="d-inline">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">
                    @foreach($filters as $key => $value)
                        @if($value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="bi bi-file-pdf"></i> Export PDF
                    </button>
                </form>

                <form method="POST" action="{{ route('laporan.excel') }}" class="d-inline">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">
                    @foreach($filters as $key => $value)
                        @if($value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="bi bi-file-excel"></i> Export Excel
                    </button>
                </form>

                <a href="{{ route('laporan.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Report Header -->
            <div class="text-center mb-4">
                <h3>Rumah Yatim Baiturrohim</h3>
                <h5>{{ $title }}</h5>
                <p class="text-muted">Tanggal Cetak: {{ now()->format('d/m/Y H:i') }}</p>
            </div>

            <!-- Report Data -->
            @if($data->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" style="font-size:0.82rem;">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama Lengkap</th>
                                <th>Tempat, Tgl Lahir</th>
                                <th>Usia</th>
                                <th>JK</th>
                                <th>Alamat</th>
                                <th>NIK</th>
                                <th>No KK</th>
                                <th>Nama Ayah</th>
                                <th>Status Ayah</th>
                                <th>Nama Ibu</th>
                                <th>Status Ibu</th>
                                <th>No. Telp Wali</th>
                                <th>Sekolah (Sekarang)</th>
                                <th>Sekolah Saat Ini</th>
                                <th>Kelas Masuk</th>
                                <th>Tgl Masuk</th>
                                <th>Tgl Keluar</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $index => $anak)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $anak->nama_lengkap }}</td>
                                    <td>{{ $anak->tempat_lahir }}, {{ $anak->tanggal_lahir->format('d/m/Y') }}</td>
                                    <td class="text-center">{{ $anak->usia }} th</td>
                                    <td class="text-center">{{ $anak->jenis_kelamin }}</td>
                                    <td>{{ $anak->alamat ?? '-' }}</td>
                                    <td>{{ $anak->nik ?? '-' }}</td>
                                    <td>{{ $anak->no_kk ?? '-' }}</td>
                                    <td>{{ $anak->nama_ayah ?? '-' }}</td>
                                    <td>{{ $anak->status_ayah ?? '-' }}</td>
                                    <td>{{ $anak->nama_ibu ?? '-' }}</td>
                                    <td>{{ $anak->status_ibu ?? '-' }}</td>
                                    <td>{{ $anak->nomor_telepon_wali ?? '-' }}</td>
                                    <td>{{ $anak->kelas_sekarang }}</td>
                                    <td>{{ $anak->sekolah_saat_ini ?? '-' }}</td>
                                    <td>{{ $anak->kelas_saat_masuk ?? '-' }}</td>
                                    <td class="text-center">{{ $anak->tanggal_masuk->format('d/m/Y') }}</td>
                                    <td class="text-center">{{ $anak->tanggal_keluar ? $anak->tanggal_keluar->format('d/m/Y') : '-' }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ $anak->is_aktif ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $anak->is_aktif ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="19" class="text-end">
                                    <strong>Total: {{ $data->count() }} anak</strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle"></i> Tidak ada data yang sesuai dengan kriteria laporan.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
