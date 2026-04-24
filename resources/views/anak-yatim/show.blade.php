@extends('layouts.app')

@section('title', 'Detail Anak Yatim')

@section('content')
<div class="mb-4">
    <h2><i class="bi bi-person-badge"></i> Detail Anak Yatim</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('anak-yatim.index') }}">Daftar Anak Yatim</a></li>
            <li class="breadcrumb-item active">{{ $anakYatim->nama_lengkap }}</li>
        </ol>
    </nav>
</div>

<div class="row">
    <!-- Photo Card -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body text-center">
                @if($anakYatim->foto)
                    <img src="{{ asset('storage/' . $anakYatim->foto) }}" 
                         alt="Foto {{ $anakYatim->nama_lengkap }}" 
                         class="img-fluid rounded mb-3"
                         style="max-height: 400px; object-fit: cover;">
                @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3" 
                         style="height: 300px;">
                        <div class="text-center">
                            <i class="bi bi-person-circle" style="font-size: 8rem; color: #dee2e6;"></i>
                            <p class="text-muted mt-2">Tidak ada foto</p>
                        </div>
                    </div>
                @endif
                
                <h4 class="mb-1">{{ $anakYatim->nama_lengkap }}</h4>
                <p class="text-muted mb-2">
                    <i class="bi {{ $anakYatim->jenis_kelamin == 'Laki-laki' ? 'bi-gender-male text-primary' : 'bi-gender-female text-danger' }}"></i>
                    {{ $anakYatim->jenis_kelamin }}
                </p>
                <p class="mb-0">
                    <span class="badge bg-primary">{{ $anakYatim->usia }} Tahun</span>
                </p>
            </div>
        </div>
    </div>

    <!-- Details Card -->
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-person-fill"></i> Biodata Pribadi</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-4 fw-semibold">Nama Lengkap</div>
                    <div class="col-sm-8">{{ $anakYatim->nama_lengkap }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-semibold">Tempat, Tanggal Lahir</div>
                    <div class="col-sm-8">
                        {{ $anakYatim->tempat_lahir }}, {{ $anakYatim->tanggal_lahir->format('d F Y') }}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-semibold">Usia</div>
                    <div class="col-sm-8">{{ $anakYatim->usia }} tahun</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-semibold">Jenis Kelamin</div>
                    <div class="col-sm-8">{{ $anakYatim->jenis_kelamin }}</div>
                </div>
                <div class="row mb-0">
                    <div class="col-sm-4 fw-semibold">Alamat</div>
                    <div class="col-sm-8">{{ $anakYatim->alamat ?? '-' }}</div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-card-text"></i> Data Identitas</h5>
            </div>
            <div class="card-body">
                @php \Carbon\Carbon::setLocale('id'); @endphp
                <div class="row mb-3">
                    <div class="col-sm-4 fw-semibold">NIK</div>
                    <div class="col-sm-8">{{ $anakYatim->nik ?? '-' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-semibold">No KK</div>
                    <div class="col-sm-8">{{ $anakYatim->no_kk ?? '-' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-semibold">Kelas Saat Masuk</div>
                    <div class="col-sm-8">{{ $anakYatim->kelas_saat_masuk ?? '-' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-semibold">Sekolah (Sekarang)</div>
                    <div class="col-sm-8">
                        <span class="badge bg-primary fs-6">{{ $anakYatim->kelas_sekarang }}</span>
                    </div>
                </div>
                <div class="row mb-0">
                    <div class="col-sm-4 fw-semibold">Estimasi Keluar</div>
                    <div class="col-sm-8">{{ $anakYatim->estimasi_keluar ? $anakYatim->estimasi_keluar->translatedFormat('F Y') : 'Belum dapat dihitung' }}</div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-people"></i> Data Orang Tua</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-4 fw-semibold">Nama Ayah</div>
                    <div class="col-sm-8">{{ $anakYatim->nama_ayah ?? '-' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-semibold">Status Ayah</div>
                    <div class="col-sm-8">{{ $anakYatim->status_ayah ?? '-' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-semibold">Nama Ibu</div>
                    <div class="col-sm-8">{{ $anakYatim->nama_ibu ?? '-' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-semibold">Status Ibu</div>
                    <div class="col-sm-8">{{ $anakYatim->status_ibu ?? '-' }}</div>
                </div>
                <div class="row mb-0">
                    <div class="col-sm-4 fw-semibold">Nomor Telepon Wali</div>
                    <div class="col-sm-8">{{ $anakYatim->nomor_telepon_wali ?? '-' }}</div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-book"></i> Data Pendidikan</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-4 fw-semibold">Pendidikan Terakhir</div>
                    <div class="col-sm-8">{{ $anakYatim->pendidikan_terakhir ?? '-' }}</div>
                </div>
                <div class="row mb-0">
                    <div class="col-sm-4 fw-semibold">Sekolah Saat Ini</div>
                    <div class="col-sm-8">{{ $anakYatim->sekolah_saat_ini ?? '-' }}</div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-house-door"></i> Data Panti</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-4 fw-semibold">Tanggal Masuk</div>
                    <div class="col-sm-8">{{ $anakYatim->tanggal_masuk->format('d F Y') }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-semibold">Tanggal Keluar</div>
                    <div class="col-sm-8">
                        {{ $anakYatim->tanggal_keluar ? $anakYatim->tanggal_keluar->format('d F Y') : 'Belum ditentukan' }}
                        @if($anakYatim->tanggal_keluar && $anakYatim->estimasi_keluar && $anakYatim->tanggal_keluar->eq($anakYatim->estimasi_keluar))
                            <small class="text-muted">(estimasi lulus SMA)</small>
                        @endif
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-semibold">Status</div>
                    <div class="col-sm-8">
                        @if($anakYatim->is_aktif)
                            <span class="badge bg-success fs-6">Aktif</span>
                        @else
                            <span class="badge bg-secondary fs-6">Tidak Aktif</span>
                        @endif
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-semibold">Lama Tinggal</div>
                    <div class="col-sm-8">
                        {{ $anakYatim->tanggal_masuk->diffForHumans(null, true) }}
                    </div>
                </div>
                <div class="row mb-0">
                    <div class="col-sm-4 fw-semibold">Data Dibuat</div>
                    <div class="col-sm-8">{{ $anakYatim->created_at->format('d F Y H:i') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="d-flex justify-content-between mt-4">
    <a href="{{ route('anak-yatim.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <div>
        <a href="{{ route('anak-yatim.edit', $anakYatim) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <button type="button" 
                class="btn btn-danger" 
                onclick="confirmDelete({{ $anakYatim->id }}, '{{ $anakYatim->nama_lengkap }}')">
            <i class="bi bi-trash"></i> Hapus
        </button>
    </div>
</div>

<!-- Delete Confirmation Form (Hidden) -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
function confirmDelete(id, nama) {
    if (confirm('Apakah Anda yakin ingin menghapus data "' + nama + '"?\n\nData yang dihapus tidak dapat dikembalikan.')) {
        const form = document.getElementById('deleteForm');
        form.action = '/anak-yatim/' + id;
        form.submit();
    }
}
</script>
@endpush
