<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Akun - Rumah Yatim Baiturrohim</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">

            {{-- Header --}}
            <div class="text-center mb-4">
                <i class="bi bi-house-heart-fill text-primary" style="font-size: 3rem;"></i>
                <h3 class="mt-2 mb-0">Rumah Yatim Baiturrohim</h3>
                <p class="text-muted">Pendaftaran Akun Orang Tua / Wali</p>
            </div>

            {{-- Alert --}}
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($anakYatimList->isEmpty())
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-info-circle-fill fs-1 text-info"></i>
                        <h5 class="mt-3">Tidak Ada Anak Yatim Tersedia</h5>
                        <p class="text-muted">Semua anak yatim aktif sudah memiliki akun orang tua, atau belum ada data anak yatim yang terdaftar.</p>
                        <p class="text-muted small">Silakan hubungi pengurus untuk informasi lebih lanjut.</p>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left"></i> Kembali ke Login
                        </a>
                    </div>
                </div>
            @else
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-person-plus-fill"></i> Buat Akun Baru</h5>
                    </div>
                    <div class="card-body p-4">

                        {{-- Info alur --}}
                        <div class="alert alert-info py-2 mb-4">
                            <i class="bi bi-info-circle"></i>
                            Setelah mendaftar, akun Anda akan <strong>menunggu persetujuan pengurus</strong> sebelum bisa digunakan.
                        </div>

                        <form method="POST" action="{{ route('register.store') }}">
                            @csrf

                            {{-- Pilih Anak Yatim --}}
                            <div class="mb-3">
                                <label for="anak_yatim_id" class="form-label fw-semibold">
                                    Nama Anak Yatim Anda <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('anak_yatim_id') is-invalid @enderror"
                                        id="anak_yatim_id" name="anak_yatim_id" required>
                                    <option value="">-- Pilih nama anak --</option>
                                    @foreach($anakYatimList as $anak)
                                        <option value="{{ $anak->id }}"
                                                data-nama-ibu="{{ $anak->nama_ibu }}"
                                                {{ old('anak_yatim_id') == $anak->id ? 'selected' : '' }}>
                                            {{ $anak->nama_lengkap }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">Pilih nama anak yatim yang Anda wakili.</div>
                                @error('anak_yatim_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Info ibu dari anak yang dipilih --}}
                            <div id="infoIbu" class="alert alert-secondary py-2 mb-3 d-none">
                                <i class="bi bi-person-heart"></i>
                                Nama Ibu tercatat: <strong id="namaIbuText"></strong>
                            </div>

                            <hr class="my-3">

                            {{-- Nama --}}
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">
                                    Nama Lengkap Anda <span class="text-danger">*</span>
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
                                <div class="form-text">Email ini digunakan untuk login.</div>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Password --}}
                            <div class="mb-3">
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
                                            onclick="togglePwd('password', this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Konfirmasi Password --}}
                            <div class="mb-4">
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
                                            onclick="togglePwd('password_confirmation', this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-send-check"></i> Daftar Sekarang
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <div class="text-center mt-3">
                <span class="text-muted">Sudah punya akun?</span>
                <a href="{{ route('login') }}" class="ms-1">Login di sini</a>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function togglePwd(id, btn) {
        const input = document.getElementById(id);
        const icon  = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }

    // Tampilkan nama ibu saat anak dipilih
    const selectAnak = document.getElementById('anak_yatim_id');
    const infoIbu    = document.getElementById('infoIbu');
    const namaIbuEl  = document.getElementById('namaIbuText');
    const nameInput  = document.getElementById('name');

    if (selectAnak) {
        selectAnak.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            const namaIbu = opt.dataset.namaIbu;
            if (opt.value && namaIbu) {
                namaIbuEl.textContent = namaIbu;
                infoIbu.classList.remove('d-none');
                // Auto-isi nama jika masih kosong
                if (!nameInput.value) nameInput.value = namaIbu;
            } else {
                infoIbu.classList.add('d-none');
            }
        });
        if (selectAnak.value) selectAnak.dispatchEvent(new Event('change'));
    }
</script>
</body>
</html>
