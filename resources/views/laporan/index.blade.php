@extends('layouts.app')

@section('title', 'Laporan - Sistem Data Anak Yatim')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Buat Laporan Data Anak Yatim</h4>
                </div>
                <div class="card-body">
                    <form id="laporanForm" method="POST" action="{{ route('laporan.preview') }}">
                        @csrf

                        <!-- Report Type Selection -->
                        <div class="mb-3">
                            <label for="type" class="form-label">Jenis Laporan</label>
                            <select class="form-select @error('type') is-invalid @enderror" 
                                    id="type" name="type" required>
                                <option value="">-- Pilih Jenis Laporan --</option>
                                <option value="semua" {{ old('type') == 'semua' ? 'selected' : '' }}>
                                    Semua Anak Yatim
                                </option>
                                <option value="usia" {{ old('type') == 'usia' ? 'selected' : '' }}>
                                    Berdasarkan Rentang Usia
                                </option>
                                <option value="pendidikan" {{ old('type') == 'pendidikan' ? 'selected' : '' }}>
                                    Berdasarkan Jenjang Pendidikan
                                </option>
                                <option value="tahun_masuk" {{ old('type') == 'tahun_masuk' ? 'selected' : '' }}>
                                    Berdasarkan Tahun Masuk
                                </option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Age Range Filter (shown when type = usia) -->
                        <div id="usiaFilter" class="filter-section" style="display: none;">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="min_age" class="form-label">Usia Minimum</label>
                                    <input type="number" class="form-control @error('min_age') is-invalid @enderror" 
                                           id="min_age" name="min_age" min="0" max="100" 
                                           value="{{ old('min_age', 0) }}">
                                    @error('min_age')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="max_age" class="form-label">Usia Maksimum</label>
                                    <input type="number" class="form-control @error('max_age') is-invalid @enderror" 
                                           id="max_age" name="max_age" min="0" max="100" 
                                           value="{{ old('max_age', 18) }}">
                                    @error('max_age')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Education Filter (shown when type = pendidikan) -->
                        <div id="pendidikanFilter" class="filter-section" style="display: none;">
                            <div class="mb-3">
                                <label for="pendidikan" class="form-label">Jenjang Pendidikan</label>
                                <select class="form-select @error('pendidikan') is-invalid @enderror" 
                                        id="pendidikan" name="pendidikan">
                                    <option value="">-- Pilih Jenjang Pendidikan --</option>
                                    <option value="TK" {{ old('pendidikan') == 'TK' ? 'selected' : '' }}>TK</option>
                                    <option value="SD" {{ old('pendidikan') == 'SD' ? 'selected' : '' }}>SD</option>
                                    <option value="SMP" {{ old('pendidikan') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                    <option value="SMA" {{ old('pendidikan') == 'SMA' ? 'selected' : '' }}>SMA</option>
                                    <option value="SMK" {{ old('pendidikan') == 'SMK' ? 'selected' : '' }}>SMK</option>
                                    <option value="Kuliah" {{ old('pendidikan') == 'Kuliah' ? 'selected' : '' }}>Kuliah</option>
                                    <option value="Belum Sekolah" {{ old('pendidikan') == 'Belum Sekolah' ? 'selected' : '' }}>Belum Sekolah</option>
                                </select>
                                @error('pendidikan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Year Filter (shown when type = tahun_masuk) -->
                        <div id="tahunFilter" class="filter-section" style="display: none;">
                            <div class="mb-3">
                                <label for="tahun" class="form-label">Tahun Masuk</label>
                                <input type="number" class="form-control @error('tahun') is-invalid @enderror" 
                                       id="tahun" name="tahun" min="1900" max="{{ date('Y') }}" 
                                       value="{{ old('tahun', date('Y')) }}">
                                @error('tahun')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-eye"></i> Preview Laporan
                            </button>
                            <button type="button" class="btn btn-danger" onclick="exportPdf()">
                                <i class="bi bi-file-pdf"></i> Export PDF
                            </button>
                            <button type="button" class="btn btn-success" onclick="exportExcel()">
                                <i class="bi bi-file-excel"></i> Export Excel
                            </button>
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Show/hide filter sections based on report type
    document.getElementById('type').addEventListener('change', function() {
        const type = this.value;
        
        // Hide all filter sections
        document.querySelectorAll('.filter-section').forEach(section => {
            section.style.display = 'none';
        });

        // Show relevant filter section
        if (type === 'usia') {
            document.getElementById('usiaFilter').style.display = 'block';
        } else if (type === 'pendidikan') {
            document.getElementById('pendidikanFilter').style.display = 'block';
        } else if (type === 'tahun_masuk') {
            document.getElementById('tahunFilter').style.display = 'block';
        }
    });

    // Trigger change event on page load to show correct filter
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('type').dispatchEvent(new Event('change'));
    });

    // Export PDF function
    function exportPdf() {
        const form = document.getElementById('laporanForm');
        form.action = '{{ route('laporan.pdf') }}';
        form.submit();
        form.action = '{{ route('laporan.preview') }}';
    }

    // Export Excel function
    function exportExcel() {
        const form = document.getElementById('laporanForm');
        form.action = '{{ route('laporan.excel') }}';
        form.submit();
        form.action = '{{ route('laporan.preview') }}';
    }
</script>
@endpush
@endsection
