@extends('layouts.app')

@section('title', 'Daftar Transaksi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-cash-stack"></i> Daftar Transaksi</h2>
    <a href="{{ route('transaksi.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Transaksi
    </a>
</div>

<!-- Search and Filter Form -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('transaksi.index') }}" id="filterForm">
            <div class="row g-3">
                <!-- Date Range -->
                <div class="col-md-3">
                    <label for="tanggal_dari" class="form-label">Tanggal Dari</label>
                    <input type="date" 
                           class="form-control" 
                           id="tanggal_dari" 
                           name="tanggal_dari" 
                           value="{{ request('tanggal_dari') }}">
                </div>

                <div class="col-md-3">
                    <label for="tanggal_sampai" class="form-label">Tanggal Sampai</label>
                    <input type="date" 
                           class="form-control" 
                           id="tanggal_sampai" 
                           name="tanggal_sampai" 
                           value="{{ request('tanggal_sampai') }}">
                </div>

                <!-- Transaction Type Filter -->
                <div class="col-md-2">
                    <label for="jenis" class="form-label">Jenis</label>
                    <select class="form-select" id="jenis" name="jenis">
                        <option value="">Semua</option>
                        <option value="penerimaan" {{ request('jenis') == 'penerimaan' ? 'selected' : '' }}>Penerimaan</option>
                        <option value="pengeluaran" {{ request('jenis') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                    </select>
                </div>

                <!-- Category Filter -->
                <div class="col-md-2">
                    <label for="kategori" class="form-label">Kategori</label>
                    <select class="form-select" id="kategori" name="kategori">
                        <option value="">Semua</option>
                        @foreach(\App\Models\Transaksi::KATEGORI_PENERIMAAN as $kat)
                            <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                        @endforeach
                        @foreach(\App\Models\Transaksi::KATEGORI_PENGELUARAN as $kat)
                            <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Search -->
                <div class="col-md-2">
                    <label for="search" class="form-label">Pencarian</label>
                    <input type="text" 
                           class="form-control" 
                           id="search" 
                           name="search" 
                           placeholder="Cari keterangan..."
                           value="{{ request('search') }}">
                </div>

                <!-- Action Buttons -->
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Cari
                    </button>
                    <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Reset Filter
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Active Filters Display -->
@if(request()->hasAny(['tanggal_dari', 'tanggal_sampai', 'jenis', 'kategori', 'search']))
<div class="mb-3">
    <span class="text-muted">Filter aktif:</span>
    @if(request('tanggal_dari') || request('tanggal_sampai'))
        <span class="badge bg-info">
            Periode: {{ request('tanggal_dari', '...') }} s/d {{ request('tanggal_sampai', '...') }}
        </span>
    @endif
    @if(request('jenis'))
        <span class="badge bg-info">Jenis: {{ ucfirst(request('jenis')) }}</span>
    @endif
    @if(request('kategori'))
        <span class="badge bg-info">Kategori: {{ request('kategori') }}</span>
    @endif
    @if(request('search'))
        <span class="badge bg-info">Pencarian: {{ request('search') }}</span>
    @endif
</div>
@endif

<!-- Data Table -->
<div class="card">
    <div class="card-body">
        @if($transaksi->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                <p class="text-muted mt-3 mb-0">Belum ada data transaksi</p>
                @if(request()->hasAny(['tanggal_dari', 'tanggal_sampai', 'jenis', 'kategori', 'search']))
                    <p class="text-muted">Coba ubah filter pencarian Anda</p>
                @endif
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Kategori</th>
                            <th class="text-end">Jumlah</th>
                            <th>Keterangan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksi as $item)
                        <tr>
                            <td>{{ $item->tanggal->format('d/m/Y') }}</td>
                            <td>
                                @if($item->isPenerimaan())
                                    <span class="badge bg-success">Penerimaan</span>
                                @else
                                    <span class="badge bg-danger">Pengeluaran</span>
                                @endif
                            </td>
                            <td>{{ $item->kategori }}</td>
                            <td class="text-end fw-semibold">{{ $item->formatted_jumlah }}</td>
                            <td>{{ Str::limit($item->keterangan, 50) }}</td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('transaksi.show', $item) }}" 
                                       class="btn btn-info" 
                                       title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('transaksi.edit', $item) }}" 
                                       class="btn btn-warning" 
                                       title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-danger" 
                                            title="Hapus"
                                            onclick="confirmDelete({{ $item->id }}, '{{ $item->tanggal->format('d/m/Y') }}')">
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
                    Menampilkan {{ $transaksi->firstItem() }} - {{ $transaksi->lastItem() }} 
                    dari {{ $transaksi->total() }} data
                </div>
                <div>
                    {{ $transaksi->links() }}
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

@push('scripts')
<script>
function confirmDelete(id, tanggal) {
    if (confirm('Apakah Anda yakin ingin menghapus transaksi tanggal "' + tanggal + '"?\n\nData yang dihapus tidak dapat dikembalikan.')) {
        const form = document.getElementById('deleteForm');
        form.action = '/transaksi/' + id;
        form.submit();
    }
}
</script>
@endpush
