@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="mb-4">
    <h2><i class="bi bi-receipt"></i> Detail Transaksi</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('transaksi.index') }}">Daftar Transaksi</a></li>
            <li class="breadcrumb-item active">Detail Transaksi</li>
        </ol>
    </nav>
</div>

<div class="row">
    <!-- Transaction Details Card -->
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-cash-stack"></i> Informasi Transaksi</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-4 fw-semibold">Tanggal</div>
                    <div class="col-sm-8">{{ $transaksi->tanggal->format('d F Y') }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-semibold">Jenis Transaksi</div>
                    <div class="col-sm-8">
                        @if($transaksi->isPenerimaan())
                            <span class="badge bg-success">Penerimaan</span>
                        @else
                            <span class="badge bg-danger">Pengeluaran</span>
                        @endif
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-semibold">Kategori</div>
                    <div class="col-sm-8">{{ $transaksi->kategori }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-semibold">Jumlah</div>
                    <div class="col-sm-8">
                        <span class="fs-4 fw-bold text-primary">{{ $transaksi->formatted_jumlah }}</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-semibold">Keterangan</div>
                    <div class="col-sm-8">{{ $transaksi->keterangan }}</div>
                </div>
                <div class="row mb-0">
                    <div class="col-sm-4 fw-semibold">Bukti Transaksi</div>
                    <div class="col-sm-8">
                        @if($transaksi->bukti_file)
                            <a href="{{ asset('storage/' . $transaksi->bukti_file) }}" 
                               target="_blank" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-file-earmark-text"></i> Lihat Bukti
                            </a>
                        @else
                            <span class="text-muted">Tidak ada bukti</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Informasi Sistem</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-4 fw-semibold">Dibuat Pada</div>
                    <div class="col-sm-8">{{ $transaksi->created_at->format('d F Y H:i') }}</div>
                </div>
                <div class="row mb-0">
                    <div class="col-sm-4 fw-semibold">Terakhir Diubah</div>
                    <div class="col-sm-8">{{ $transaksi->updated_at->format('d F Y H:i') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Card -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-3">
                    @if($transaksi->isPenerimaan())
                        <i class="bi bi-arrow-down-circle-fill text-success" style="font-size: 4rem;"></i>
                        <h5 class="mt-2 text-success">Penerimaan Kas</h5>
                    @else
                        <i class="bi bi-arrow-up-circle-fill text-danger" style="font-size: 4rem;"></i>
                        <h5 class="mt-2 text-danger">Pengeluaran Kas</h5>
                    @endif
                </div>
                
                <div class="bg-light rounded p-3 mb-3">
                    <small class="text-muted d-block">Jumlah</small>
                    <h3 class="mb-0 text-primary">{{ $transaksi->formatted_jumlah }}</h3>
                </div>

                <div class="bg-light rounded p-3">
                    <small class="text-muted d-block">Kategori</small>
                    <h6 class="mb-0">{{ $transaksi->kategori }}</h6>
                </div>
            </div>
        </div>

        @if($transaksi->bukti_file)
        <div class="card mt-3">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="bi bi-paperclip"></i> Lampiran</h6>
            </div>
            <div class="card-body text-center">
                @php
                    $extension = pathinfo($transaksi->bukti_file, PATHINFO_EXTENSION);
                @endphp
                
                @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png']))
                    <img src="{{ asset('storage/' . $transaksi->bukti_file) }}" 
                         alt="Bukti Transaksi" 
                         class="img-fluid rounded mb-2"
                         style="max-height: 200px;">
                @else
                    <i class="bi bi-file-earmark-pdf-fill text-danger" style="font-size: 4rem;"></i>
                @endif
                
                <div class="mt-2">
                    <a href="{{ asset('storage/' . $transaksi->bukti_file) }}" 
                       target="_blank" 
                       class="btn btn-sm btn-primary">
                        <i class="bi bi-download"></i> Download
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Action Buttons -->
<div class="d-flex justify-content-between mt-4">
    <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <div>
        <a href="{{ route('transaksi.edit', $transaksi) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <button type="button" 
                class="btn btn-danger" 
                onclick="confirmDelete({{ $transaksi->id }}, '{{ $transaksi->tanggal->format('d/m/Y') }}')">
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
function confirmDelete(id, tanggal) {
    if (confirm('Apakah Anda yakin ingin menghapus transaksi tanggal "' + tanggal + '"?\n\nData yang dihapus tidak dapat dikembalikan.')) {
        const form = document.getElementById('deleteForm');
        form.action = '/transaksi/' + id;
        form.submit();
    }
}
</script>
@endpush
