@extends('layouts.app')

@section('title', 'Buat Akun Orang Tua / Wali')

@section('content')
<div class="mb-4">
    <h2><i class="bi bi-person-heart"></i> Buat Akun Orang Tua / Wali</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Manajemen User</a></li>
            <li class="breadcrumb-item active">Buat Akun Orang Tua</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-person-plus-fill"></i> Data Akun</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('users.orang-tua.store') }}">
                    @csrf
                    {{-- Role tersembunyi, selalu orang_tua --}}
                    <input type="hidden" name="role" value="orang_tua">

                    {{-- Pilih Anak Yatim --}}
                    <div class="mb-3">
                        <label for="anak_yatim_id" class="form-label fw-semibold">
                            Anak Yatim yang Diwakili <span class="text-danger">*</span>
                        </label>
                        @if($anakYatimList->isEmpty())
                            <div class="alert alert-warning mb-0">
                                <i class="bi bi-exclamation-triangle"></i>
                                Semua anak yatim aktif sudah memiliki akun orang tua.
                                Tidak ada anak yang tersedia.
                            </div>
                        @else
                            <select class="form-select @error('anak_yatim_id') is-invalid @enderror"
                                    id="anak_yatim_id" name="anak_yatim_id" required>
                                <option value="">-- Pilih nama anak yatim --</option>
                                @foreach($anakYatimList as $anak)
                                    <option value="{{ $anak->id }}"
                                            data-nama-ibu="{{ $anak->nama_ibu }}"
                                            data-telepon="{{ $anak->nomor_telepon_wali }}"
                                            {{ old('anak_yatim_id') == $anak->id ? 'selected' : '' }}>
                                        {{ $anak->nama_lengkap }}
                                        @if($anak->nama_ibu) — Ibu: {{ $anak->nama_ibu }} @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('anak_yatim_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Hanya anak yatim aktif yang belum memiliki akun orang tua yang ditampilkan.</div>
                        @endif
                    </div>

                    {{-- Info anak yang dipilih (auto-fill hint) --}}
                    <div id="infoAnak" class="alert alert-info d-none mb-3">
                        <i class="bi bi-info-circle"></i>
                        <span id="infoAnakText"></span>
                    </div>

                    <hr class="my-4">
                    <h6 class="text-primary mb-3"><i class="bi bi-key-fill"></i> Data Login</h6>

                    {{-- Nama --}}
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">
                            Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name"
                               value="{{ old('name') }}"
                               placeholder="Nama orang tua / wali"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">
                            Email <span class="text-danger">*</span>
                        </label>
                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email"
                               value="{{ old('email') }}"
                               placeholder="email@contoh.com"
                               required>
                        <div class="form-text">Email ini digunakan untuk login ke sistem.</div>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label fw-semibold">
                                Password <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       id="password" name="password"
                                       placeholder="Minimal 8 karakter"
                                       required>
                                <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('password', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label fw-semibold">
                                Konfirmasi Password <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="password"
                                       class="form-control"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       placeholder="Ulangi password"
                                       required>
                                <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('password_confirmation', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary"
                                {{ $anakYatimList->isEmpty() ? 'disabled' : '' }}>
                            <i class="bi bi-person-check-fill"></i> Buat Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Panel Info --}}
    <div class="col-md-4">
        <div class="card border-info">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="bi bi-info-circle"></i> Informasi</h6>
            </div>
            <div class="card-body">
                <p class="small mb-2">Akun <strong>Orang Tua / Wali</strong> memiliki akses untuk:</p>
                <ul class="small mb-3">
                    <li><i class="bi bi-check-circle text-success"></i> Submit absensi bulanan</li>
                    <li><i class="bi bi-check-circle text-success"></i> Melihat data anak yatim</li>
                    <li><i class="bi bi-check-circle text-success"></i> Mengubah data diri anak yatim</li>
                </ul>
                <hr>
                <p class="small mb-2">Akun ini <strong>tidak dapat</strong>:</p>
                <ul class="small mb-0">
                    <li><i class="bi bi-x-circle text-danger"></i> Mengelola transaksi keuangan</li>
                    <li><i class="bi bi-x-circle text-danger"></i> Melihat data anak yatim lain</li>
                    <li><i class="bi bi-x-circle text-danger"></i> Mengakses laporan</li>
                    <li><i class="bi bi-x-circle text-danger"></i> Mengelola user</li>
                </ul>
            </div>
        </div>

        <div class="card mt-3 border-warning">
            <div class="card-body">
                <h6 class="text-warning"><i class="bi bi-exclamation-triangle"></i> Catatan</h6>
                <p class="small mb-0">
                    Setiap anak yatim hanya dapat memiliki <strong>satu akun</strong> orang tua / wali.
                    Pastikan data yang dimasukkan sudah benar sebelum menyimpan.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Toggle show/hide password
    function togglePassword(fieldId, btn) {
        const input = document.getElementById(fieldId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }

    // Tampilkan info anak yang dipilih
    const selectAnak = document.getElementById('anak_yatim_id');
    const infoAnak   = document.getElementById('infoAnak');
    const infoText   = document.getElementById('infoAnakText');
    const nameInput  = document.getElementById('name');

    if (selectAnak) {
        selectAnak.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            if (!opt.value) {
                infoAnak.classList.add('d-none');
                return;
            }
            const namaIbu   = opt.dataset.namaIbu;
            const telepon   = opt.dataset.telepon;
            let info = '';
            if (namaIbu) info += `Nama Ibu: <strong>${namaIbu}</strong>`;
            if (telepon) info += (info ? ' &nbsp;|&nbsp; ' : '') + `Telepon: <strong>${telepon}</strong>`;
            if (info) {
                infoText.innerHTML = info;
                infoAnak.classList.remove('d-none');
                // Auto-isi nama jika field masih kosong
                if (!nameInput.value && namaIbu) {
                    nameInput.value = namaIbu;
                }
            } else {
                infoAnak.classList.add('d-none');
            }
        });

        // Trigger jika ada old value
        if (selectAnak.value) selectAnak.dispatchEvent(new Event('change'));
    }
</script>
@endpush
