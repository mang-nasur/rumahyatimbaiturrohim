@extends('layouts.app')

@section('title', 'Edit Transaksi')

@section('content')
<div class="mb-4">
    <h2><i class="bi bi-pencil-square"></i> Edit Transaksi</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('transaksi.index') }}">Daftar Transaksi</a></li>
            <li class="breadcrumb-item"><a href="{{ route('transaksi.show', $transaksi) }}">Detail Transaksi</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('transaksi.update', $transaksi) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Data Transaksi -->
            <h5 class="mb-3 text-primary"><i class="bi bi-cash-stack"></i> Data Transaksi</h5>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" 
                           class="form-control @error('tanggal') is-invalid @enderror" 
                           id="tanggal" 
                           name="tanggal" 
                           value="{{ old('tanggal', $transaksi->tanggal->format('Y-m-d')) }}"
                           max="{{ date('Y-m-d') }}"
                           required>
                    @error('tanggal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="jenis" class="form-label">Jenis Transaksi <span class="text-danger">*</span></label>
                    <select class="form-select @error('jenis') is-invalid @enderror" 
                            id="jenis" 
                            name="jenis"
                            onchange="updateKategoriOptions()"
                            required>
                        <option value="">Pilih jenis transaksi</option>
                        <option value="penerimaan" {{ old('jenis', $transaksi->jenis) == 'penerimaan' ? 'selected' : '' }}>Penerimaan</option>
                        <option value="pengeluaran" {{ old('jenis', $transaksi->jenis) == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                    </select>
                    @error('jenis')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                    <select class="form-select @error('kategori') is-invalid @enderror" 
                            id="kategori" 
                            name="kategori"
                            required>
                        <option value="">Pilih kategori</option>
                    </select>
                    @error('kategori')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="jumlah" class="form-label">Jumlah (Rp) <span class="text-danger">*</span></label>
                    <input type="number" 
                           class="form-control @error('jumlah') is-invalid @enderror" 
                           id="jumlah" 
                           name="jumlah" 
                           value="{{ old('jumlah', $transaksi->jumlah) }}"
                           placeholder="Masukkan jumlah"
                           min="0.01"
                           step="0.01"
                           required>
                    @error('jumlah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan <span class="text-danger">*</span></label>
                <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                          id="keterangan" 
                          name="keterangan" 
                          rows="3"
                          placeholder="Masukkan keterangan transaksi"
                          required>{{ old('keterangan', $transaksi->keterangan) }}</textarea>
                @error('keterangan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Bukti File -->
            <h5 class="mb-3 mt-4 text-primary"><i class="bi bi-file-earmark-arrow-up"></i> Bukti Transaksi</h5>

            @if($transaksi->bukti_file)
            <div class="mb-3">
                <label class="form-label">Bukti Saat Ini</label>
                <div>
                    <a href="{{ asset('storage/' . $transaksi->bukti_file) }}" 
                       target="_blank" 
                       class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-file-earmark-text"></i> Lihat Bukti
                    </a>
                </div>
                <small class="form-text text-muted">Upload file baru jika ingin mengganti bukti yang ada</small>
            </div>
            @endif

            <div class="mb-3">
                <label for="bukti_file" class="form-label">Upload Bukti Baru (Opsional)</label>
                <input type="file" 
                       class="form-control @error('bukti_file') is-invalid @enderror" 
                       id="bukti_file" 
                       name="bukti_file"
                       accept=".pdf,.jpg,.jpeg,.png">
                <small class="form-text text-muted">Format: PDF, JPG, JPEG, PNG. Maksimal 2MB</small>
                @error('bukti_file')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                <a href="{{ route('transaksi.show', $transaksi) }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Kategori options
const kategoriPenerimaan = @json(\App\Models\Transaksi::KATEGORI_PENERIMAAN);
const kategoriPengeluaran = @json(\App\Models\Transaksi::KATEGORI_PENGELUARAN);

function updateKategoriOptions() {
    const jenisSelect = document.getElementById('jenis');
    const kategoriSelect = document.getElementById('kategori');
    const selectedJenis = jenisSelect.value;
    
    // Clear existing options
    kategoriSelect.innerHTML = '<option value="">Pilih kategori</option>';
    
    // Add options based on selected jenis
    let options = [];
    if (selectedJenis === 'penerimaan') {
        options = kategoriPenerimaan;
    } else if (selectedJenis === 'pengeluaran') {
        options = kategoriPengeluaran;
    }
    
    options.forEach(function(kategori) {
        const option = document.createElement('option');
        option.value = kategori;
        option.textContent = kategori;
        kategoriSelect.appendChild(option);
    });
    
    // Restore old value or existing value
    const oldKategori = '{{ old('kategori', $transaksi->kategori) }}';
    if (oldKategori) {
        kategoriSelect.value = oldKategori;
    }
}

// Initialize kategori options on page load
document.addEventListener('DOMContentLoaded', function() {
    updateKategoriOptions();
});
</script>
@endpush
