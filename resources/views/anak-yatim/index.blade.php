@extends('layouts.app')

@section('title', 'Daftar Anak Yatim')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-people-fill"></i> Daftar Anak Yatim</h2>
    <div class="d-flex gap-2">
        {{-- Export dengan filter yang aktif --}}
        <a href="{{ route('anak-yatim.export.excel', request()->query()) }}"
           class="btn btn-success">
            <i class="bi bi-file-earmark-excel"></i> Excel
        </a>
        <a href="{{ route('anak-yatim.export.pdf', request()->query()) }}"
           class="btn btn-danger">
            <i class="bi bi-file-earmark-pdf"></i> PDF
        </a>
        <a href="{{ route('anak-yatim.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Data
        </a>
    </div>
</div>

<!-- Search and Filter Form -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('anak-yatim.index') }}" id="filterForm">
            <div class="row g-3">
                <!-- Search -->
                <div class="col-md-4">
                    <label for="search" class="form-label">Pencarian</label>
                    <input type="text"
                           class="form-control"
                           id="search"
                           name="search"
                           placeholder="Cari nama anak, ayah, atau ibu..."
                           value="{{ request('search') }}">
                </div>

                <!-- Status Aktif Filter -->
                <div class="col-md-2">
                    <label for="status_aktif" class="form-label">Status</label>
                    <select class="form-select" id="status_aktif" name="status_aktif">
                        <option value="">Semua</option>
                        <option value="aktif" {{ request('status_aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="non_aktif" {{ request('status_aktif') == 'non_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="col-md-6 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Cari
                    </button>
                    <a href="{{ route('anak-yatim.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Reset
                    </a>
                </div>
            </div>

            <!-- Row 2: Usia & Kelas Range -->
            <div class="row g-3 mt-1">
                <!-- Usia Min -->
                <div class="col-md-3">
                    <label class="form-label">Usia (tahun)</label>
                    <div class="input-group">
                        <span class="input-group-text">Min</span>
                        <input type="number"
                               class="form-control"
                               name="min_age"
                               placeholder="0"
                               min="0" max="99"
                               value="{{ request('min_age') }}">
                        <span class="input-group-text">Max</span>
                        <input type="number"
                               class="form-control"
                               name="max_age"
                               placeholder="99"
                               min="0" max="99"
                               value="{{ request('max_age') }}">
                    </div>
                </div>

                <!-- Kelas Min -->
                <div class="col-md-5">
                    <label class="form-label">Kelas Saat Masuk</label>
                    <div class="input-group">
                        <span class="input-group-text">Min</span>
                        <select class="form-select" name="kelas_min">
                            <option value="">-</option>
                            @foreach(['Kelas 1 SD','Kelas 2 SD','Kelas 3 SD','Kelas 4 SD','Kelas 5 SD','Kelas 6 SD',
                                      'Kelas 1 SMP','Kelas 2 SMP','Kelas 3 SMP',
                                      'Kelas 1 SMA','Kelas 2 SMA','Kelas 3 SMA'] as $kelas)
                                <option value="{{ $kelas }}" {{ request('kelas_min') == $kelas ? 'selected' : '' }}>
                                    {{ $kelas }}
                                </option>
                            @endforeach
                        </select>
                        <span class="input-group-text">Max</span>
                        <select class="form-select" name="kelas_max">
                            <option value="">-</option>
                            @foreach(['Kelas 1 SD','Kelas 2 SD','Kelas 3 SD','Kelas 4 SD','Kelas 5 SD','Kelas 6 SD',
                                      'Kelas 1 SMP','Kelas 2 SMP','Kelas 3 SMP',
                                      'Kelas 1 SMA','Kelas 2 SMA','Kelas 3 SMA'] as $kelas)
                                <option value="{{ $kelas }}" {{ request('kelas_max') == $kelas ? 'selected' : '' }}>
                                    {{ $kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Active Filters Display -->
@if(request()->hasAny(['search', 'kelas_saat_masuk', 'kelas_min', 'kelas_max', 'min_age', 'max_age', 'status_aktif']))
<div class="mb-3">
    <span class="text-muted">Filter aktif:</span>
    @if(request('search'))
        <span class="badge bg-info">Pencarian: {{ request('search') }}</span>
    @endif
    @if(request('min_age') || request('max_age'))
        <span class="badge bg-info">
            Usia: {{ request('min_age', '0') }} &ndash; {{ request('max_age', '99') }} tahun
        </span>
    @endif
    @if(request('kelas_min') || request('kelas_max'))
        <span class="badge bg-info">
            Kelas: {{ request('kelas_min') ?: 'Kelas 1 SD' }} &ndash; {{ request('kelas_max') ?: 'Kelas 3 SMA' }}
        </span>
    @endif
    @if(request('kelas_saat_masuk'))
        <span class="badge bg-info">Kelas: {{ request('kelas_saat_masuk') }}</span>
    @endif
    @if(request('status_aktif'))
        <span class="badge bg-info">Status: {{ request('status_aktif') == 'aktif' ? 'Aktif' : 'Tidak Aktif' }}</span>
    @endif
</div>
@endif

<!-- Data Table -->
<div class="card">
    <div class="card-body">
        @if($anakYatim->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                <p class="text-muted mt-3 mb-0">Belum ada data anak yatim</p>
                @if(request()->hasAny(['search', 'min_age', 'max_age', 'pendidikan', 'tahun_masuk']))
                    <p class="text-muted">Coba ubah filter pencarian Anda</p>
                @endif
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th>Tempat, Tanggal Lahir</th>
                            <th>NIK</th>
                            <th>No. KK</th>
                            <th>Jenis Kelamin</th>
                            <th>Alamat</th>
                            <th>Nama Ibu</th>
                            <th>Nomor Wali</th>
                            <th>Kelas</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($anakYatim as $anak)
                        <tr>
                            <td>
                                <a href="{{ route('anak-yatim.show', $anak) }}" class="text-decoration-none fw-semibold">
                                    {{ $anak->nama_lengkap }}
                                </a>
                            </td>
                            <td>
                                {{ $anak->tempat_lahir }}, 
                                {{ $anak->tanggal_lahir->format('d/m/Y') }}
                                <br>
                                <small class="text-muted">({{ $anak->usia }} tahun)</small>
                            </td>
                            <td>
                                <small>{{ $anak->nik ?: '-' }}</small>
                            </td>
                            <td>
                                <small>{{ $anak->no_kk ?: '-' }}</small>
                            </td>
                            <td>
                                <i class="bi {{ $anak->jenis_kelamin == 'Laki-laki' ? 'bi-gender-male text-primary' : 'bi-gender-female text-danger' }}"></i>
                                {{ $anak->jenis_kelamin == 'Laki-laki' ? 'L' : 'P' }}
                            </td>
                            <td>
                                <small>{{ Str::limit($anak->alamat ?: '-', 40) }}</small>
                            </td>
                            <td>{{ $anak->nama_ibu ?: '-' }}</td>
                            <td>
                                <small>{{ $anak->nomor_telepon_wali ?: '-' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $anak->kelas_sekarang }}</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('anak-yatim.show', $anak) }}" 
                                       class="btn btn-info" 
                                       title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('anak-yatim.edit', $anak) }}" 
                                       class="btn btn-warning" 
                                       title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-danger" 
                                            title="Hapus"
                                            onclick="confirmDelete({{ $anak->id }}, '{{ $anak->nama_lengkap }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Menampilkan {{ $anakYatim->firstItem() }} - {{ $anakYatim->lastItem() }} 
                    dari {{ $anakYatim->total() }} data
                </div>
                <div>
                    {{ $anakYatim->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Form (Hidden) -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('styles')
<style>
    /* Compact table styling for better readability */
    .table-sm td, .table-sm th {
        padding: 0.5rem 0.3rem;
        font-size: 0.875rem;
        vertical-align: middle;
    }
    
    .table-sm td small {
        font-size: 0.8rem;
    }
    
    /* Make table scrollable on small screens */
    @media (max-width: 1400px) {
        .table-responsive {
            overflow-x: auto;
        }
        
        .table {
            min-width: 1200px;
        }
    }
</style>
@endpush

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
