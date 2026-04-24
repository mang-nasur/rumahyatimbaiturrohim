@extends('layouts.app')

@section('title', 'Tambah Data Anak Yatim')

@section('content')
<div class="mb-4">
    <h2><i class="bi bi-plus-circle"></i> Tambah Data Anak Yatim</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('anak-yatim.index') }}">Daftar Anak Yatim</a></li>
            <li class="breadcrumb-item active">Tambah Data</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('anak-yatim.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- Biodata Pribadi -->
            <h5 class="mb-3 text-primary"><i class="bi bi-person-fill"></i> Biodata Pribadi</h5>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control @error('nama_lengkap') is-invalid @enderror" 
                           id="nama_lengkap" 
                           name="nama_lengkap" 
                           value="{{ old('nama_lengkap') }}"
                           placeholder="Masukkan nama lengkap"
                           required>
                    @error('nama_lengkap')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select class="form-select @error('jenis_kelamin') is-invalid @enderror" 
                            id="jenis_kelamin" 
                            name="jenis_kelamin"
                            required>
                        <option value="">Pilih jenis kelamin</option>
                        <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="tempat_lahir" class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control @error('tempat_lahir') is-invalid @enderror" 
                           id="tempat_lahir" 
                           name="tempat_lahir" 
                           value="{{ old('tempat_lahir') }}"
                           placeholder="Masukkan tempat lahir"
                           required>
                    @error('tempat_lahir')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                    <input type="date" 
                           class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                           id="tanggal_lahir" 
                           name="tanggal_lahir" 
                           value="{{ old('tanggal_lahir') }}"
                           max="{{ date('Y-m-d') }}"
                           required>
                    @error('tanggal_lahir')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea class="form-control @error('alamat') is-invalid @enderror" 
                          id="alamat" 
                          name="alamat" 
                          rows="3"
                          placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                @error('alamat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Data Identitas -->
            <h5 class="mb-3 mt-4 text-primary"><i class="bi bi-card-text"></i> Data Identitas</h5>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="nik" class="form-label">NIK</label>
                    <input type="text" 
                           class="form-control @error('nik') is-invalid @enderror" 
                           id="nik" 
                           name="nik" 
                           value="{{ old('nik') }}"
                           maxlength="16"
                           placeholder="Masukkan 16 digit NIK">
                    @error('nik')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="no_kk" class="form-label">No KK</label>
                    <input type="text" 
                           class="form-control @error('no_kk') is-invalid @enderror" 
                           id="no_kk" 
                           name="no_kk" 
                           value="{{ old('no_kk') }}"
                           maxlength="16"
                           placeholder="Masukkan 16 digit No KK">
                    @error('no_kk')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="kelas_saat_masuk" class="form-label">Kelas Saat Masuk</label>
                <select class="form-select @error('kelas_saat_masuk') is-invalid @enderror" 
                        id="kelas_saat_masuk" 
                        name="kelas_saat_masuk">
                    <option value="">Pilih kelas saat masuk</option>
                    <option value="Belum Sekolah" {{ old('kelas_saat_masuk', 'Belum Sekolah') == 'Belum Sekolah' ? 'selected' : '' }}>Belum Sekolah</option>
                    <option value="Kelas 1 SD" {{ old('kelas_saat_masuk', 'Belum Sekolah') == 'Kelas 1 SD' ? 'selected' : '' }}>Kelas 1 SD</option>
                    <option value="Kelas 2 SD" {{ old('kelas_saat_masuk', 'Belum Sekolah') == 'Kelas 2 SD' ? 'selected' : '' }}>Kelas 2 SD</option>
                    <option value="Kelas 3 SD" {{ old('kelas_saat_masuk', 'Belum Sekolah') == 'Kelas 3 SD' ? 'selected' : '' }}>Kelas 3 SD</option>
                    <option value="Kelas 4 SD" {{ old('kelas_saat_masuk', 'Belum Sekolah') == 'Kelas 4 SD' ? 'selected' : '' }}>Kelas 4 SD</option>
                    <option value="Kelas 5 SD" {{ old('kelas_saat_masuk', 'Belum Sekolah') == 'Kelas 5 SD' ? 'selected' : '' }}>Kelas 5 SD</option>
                    <option value="Kelas 6 SD" {{ old('kelas_saat_masuk', 'Belum Sekolah') == 'Kelas 6 SD' ? 'selected' : '' }}>Kelas 6 SD</option>
                    <option value="Kelas 1 SMP" {{ old('kelas_saat_masuk', 'Belum Sekolah') == 'Kelas 1 SMP' ? 'selected' : '' }}>Kelas 1 SMP</option>
                    <option value="Kelas 2 SMP" {{ old('kelas_saat_masuk', 'Belum Sekolah') == 'Kelas 2 SMP' ? 'selected' : '' }}>Kelas 2 SMP</option>
                    <option value="Kelas 3 SMP" {{ old('kelas_saat_masuk', 'Belum Sekolah') == 'Kelas 3 SMP' ? 'selected' : '' }}>Kelas 3 SMP</option>
                    <option value="Kelas 1 SMA" {{ old('kelas_saat_masuk', 'Belum Sekolah') == 'Kelas 1 SMA' ? 'selected' : '' }}>Kelas 1 SMA</option>
                    <option value="Kelas 2 SMA" {{ old('kelas_saat_masuk', 'Belum Sekolah') == 'Kelas 2 SMA' ? 'selected' : '' }}>Kelas 2 SMA</option>
                    <option value="Kelas 3 SMA" {{ old('kelas_saat_masuk', 'Belum Sekolah') == 'Kelas 3 SMA' ? 'selected' : '' }}>Kelas 3 SMA</option>
                </select>
                @error('kelas_saat_masuk')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Data Orang Tua -->
            <h5 class="mb-3 mt-4 text-primary"><i class="bi bi-people"></i> Data Orang Tua</h5>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="nama_ayah" class="form-label">Nama Ayah</label>
                    <input type="text" 
                           class="form-control @error('nama_ayah') is-invalid @enderror" 
                           id="nama_ayah" 
                           name="nama_ayah" 
                           value="{{ old('nama_ayah') }}"
                           placeholder="Masukkan nama ayah">
                    @error('nama_ayah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="status_ayah" class="form-label">Status Ayah</label>
                    <select class="form-select @error('status_ayah') is-invalid @enderror" 
                            id="status_ayah" 
                            name="status_ayah">
                        <option value="">Pilih status</option>
                        <option value="Meninggal" {{ old('status_ayah') == 'Meninggal' ? 'selected' : '' }}>Meninggal</option>
                        <option value="Tidak Diketahui" {{ old('status_ayah') == 'Tidak Diketahui' ? 'selected' : '' }}>Tidak Diketahui</option>
                        <option value="Tidak Mampu" {{ old('status_ayah') == 'Tidak Mampu' ? 'selected' : '' }}>Tidak Mampu</option>
                    </select>
                    @error('status_ayah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="nama_ibu" class="form-label">Nama Ibu</label>
                    <input type="text" 
                           class="form-control @error('nama_ibu') is-invalid @enderror" 
                           id="nama_ibu" 
                           name="nama_ibu" 
                           value="{{ old('nama_ibu') }}"
                           placeholder="Masukkan nama ibu">
                    @error('nama_ibu')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="status_ibu" class="form-label">Status Ibu</label>
                    <select class="form-select @error('status_ibu') is-invalid @enderror" 
                            id="status_ibu" 
                            name="status_ibu">
                        <option value="">Pilih status</option>
                        <option value="Hidup" {{ old('status_ibu') == 'Hidup' ? 'selected' : '' }}>Hidup</option>
                        <option value="Meninggal" {{ old('status_ibu') == 'Meninggal' ? 'selected' : '' }}>Meninggal</option>
                        <option value="Tidak Diketahui" {{ old('status_ibu') == 'Tidak Diketahui' ? 'selected' : '' }}>Tidak Diketahui</option>
                    </select>
                    @error('status_ibu')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="nomor_telepon_wali" class="form-label">Nomor Telepon Wali</label>
                <input type="text" 
                       class="form-control @error('nomor_telepon_wali') is-invalid @enderror" 
                       id="nomor_telepon_wali" 
                       name="nomor_telepon_wali" 
                       value="{{ old('nomor_telepon_wali') }}"
                       placeholder="Contoh: +628123456789 atau 08123456789">
                <small class="form-text text-muted">Hanya boleh berisi angka, tanda + dan -</small>
                @error('nomor_telepon_wali')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Data Pendidikan -->
            <h5 class="mb-3 mt-4 text-primary"><i class="bi bi-book"></i> Data Pendidikan</h5>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="pendidikan_terakhir" class="form-label">Pendidikan Terakhir</label>
                    <select class="form-select @error('pendidikan_terakhir') is-invalid @enderror" 
                            id="pendidikan_terakhir" 
                            name="pendidikan_terakhir">
                        <option value="">Pilih pendidikan</option>
                        <option value="Belum Sekolah" {{ old('pendidikan_terakhir', 'Belum Sekolah') == 'Belum Sekolah' ? 'selected' : '' }}>Belum Sekolah</option>
                        <option value="TK" {{ old('pendidikan_terakhir', 'Belum Sekolah') == 'TK' ? 'selected' : '' }}>TK</option>
                        <option value="SD" {{ old('pendidikan_terakhir', 'Belum Sekolah') == 'SD' ? 'selected' : '' }}>SD</option>
                        <option value="SMP" {{ old('pendidikan_terakhir', 'Belum Sekolah') == 'SMP' ? 'selected' : '' }}>SMP</option>
                        <option value="SMA" {{ old('pendidikan_terakhir', 'Belum Sekolah') == 'SMA' ? 'selected' : '' }}>SMA</option>
                    </select>
                    @error('pendidikan_terakhir')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="sekolah_saat_ini" class="form-label">Sekolah Saat Ini</label>
                    <input type="text" 
                           class="form-control @error('sekolah_saat_ini') is-invalid @enderror" 
                           id="sekolah_saat_ini" 
                           name="sekolah_saat_ini" 
                           value="{{ old('sekolah_saat_ini') }}"
                           placeholder="Masukkan nama sekolah">
                    @error('sekolah_saat_ini')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Data Panti -->
            <h5 class="mb-3 mt-4 text-primary"><i class="bi bi-house-door"></i> Data Panti</h5>

            <div class="mb-3">
                <label for="tanggal_masuk" class="form-label">Tanggal Masuk Panti <span class="text-danger">*</span></label>
                <input type="date" 
                       class="form-control @error('tanggal_masuk') is-invalid @enderror" 
                       id="tanggal_masuk" 
                       name="tanggal_masuk" 
                       value="{{ old('tanggal_masuk', date('Y-m-d')) }}"
                       max="{{ date('Y-m-d') }}"
                       required>
                @error('tanggal_masuk')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- <div class="mb-3">
                <label for="tanggal_keluar" class="form-label">Tanggal Keluar</label>
                <input type="date" 
                       class="form-control @error('tanggal_keluar') is-invalid @enderror" 
                       id="tanggal_keluar" 
                       name="tanggal_keluar" 
                       value="{{ old('tanggal_keluar') }}">
                <small class="form-text text-muted">
                    Kosongkan untuk menghitung otomatis dari kelas saat masuk (estimasi lulus SMA).
                </small>
                @error('tanggal_keluar')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div> --}}

            <div class="mb-3">
                <label class="form-label">Status Keaktifan</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="is_aktif" id="is_aktif_1" value="1"
                           {{ old('is_aktif', '1') == '1' ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_aktif_1">
                        <span class="badge bg-success">Aktif</span> — masih terdaftar di yayasan
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="is_aktif" id="is_aktif_0" value="0"
                           {{ old('is_aktif') == '0' ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_aktif_0">
                        <span class="badge bg-secondary">Tidak Aktif</span> — sudah keluar / lulus SMA
                    </label>
                </div>
            </div>

            <!-- Foto -->
            <h5 class="mb-3 mt-4 text-primary"><i class="bi bi-camera"></i> Foto</h5>

            <div class="mb-3">
                <label for="foto" class="form-label">Upload Foto</label>
                <input type="file" 
                       class="form-control @error('foto') is-invalid @enderror" 
                       id="foto" 
                       name="foto"
                       accept="image/jpeg,image/jpg,image/png"
                       onchange="previewImage(event)">
                <small class="form-text text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB</small>
                @error('foto')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Photo Preview -->
            <div id="photoPreview" class="mb-3" style="display: none;">
                <label class="form-label">Preview Foto</label>
                <div>
                    <img id="previewImg" src="" alt="Preview" class="img-thumbnail" style="max-width: 300px; max-height: 300px;">
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                <a href="{{ route('anak-yatim.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('photoPreview');
    const previewImg = document.getElementById('previewImg');
    
    if (file) {
        // Validate file size (2MB = 2097152 bytes)
        if (file.size > 2097152) {
            alert('Ukuran file terlalu besar! Maksimal 2MB');
            event.target.value = '';
            preview.style.display = 'none';
            return;
        }
        
        // Validate file type
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!validTypes.includes(file.type)) {
            alert('Format file tidak valid! Gunakan JPG, JPEG, atau PNG');
            event.target.value = '';
            preview.style.display = 'none';
            return;
        }
        
        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
}
</script>
@endpush
