@extends('layouts.app')

@section('title', 'Ubah Data Diri Anak')

@section('content')
<div class="mb-4">
    <h2><i class="bi bi-pencil-square"></i> Ubah Data Diri Anak</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Ubah Data Diri</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('anak-yatim.update', $anakYatim) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Biodata Pribadi --}}
            <h5 class="mb-3 text-primary"><i class="bi bi-person-fill"></i> Biodata Pribadi</h5>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text"
                           class="form-control @error('nama_lengkap') is-invalid @enderror"
                           id="nama_lengkap" name="nama_lengkap"
                           value="{{ old('nama_lengkap', $anakYatim->nama_lengkap) }}"
                           required>
                    @error('nama_lengkap')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select class="form-select @error('jenis_kelamin') is-invalid @enderror"
                            id="jenis_kelamin" name="jenis_kelamin" required>
                        <option value="Laki-laki" {{ old('jenis_kelamin', $anakYatim->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin', $anakYatim->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
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
                           id="tempat_lahir" name="tempat_lahir"
                           value="{{ old('tempat_lahir', $anakYatim->tempat_lahir) }}"
                           required>
                    @error('tempat_lahir')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                    <input type="date"
                           class="form-control @error('tanggal_lahir') is-invalid @enderror"
                           id="tanggal_lahir" name="tanggal_lahir"
                           value="{{ old('tanggal_lahir', $anakYatim->tanggal_lahir->format('Y-m-d')) }}"
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
                          id="alamat" name="alamat" rows="3">{{ old('alamat', $anakYatim->alamat) }}</textarea>
                @error('alamat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Data Identitas --}}
            <h5 class="mb-3 mt-4 text-primary"><i class="bi bi-card-text"></i> Data Identitas</h5>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="nik" class="form-label">NIK</label>
                    <input type="text"
                           class="form-control @error('nik') is-invalid @enderror"
                           id="nik" name="nik"
                           value="{{ old('nik', $anakYatim->nik) }}"
                           maxlength="16"
                           placeholder="16 digit NIK">
                    @error('nik')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="no_kk" class="form-label">No KK</label>
                    <input type="text"
                           class="form-control @error('no_kk') is-invalid @enderror"
                           id="no_kk" name="no_kk"
                           value="{{ old('no_kk', $anakYatim->no_kk) }}"
                           maxlength="16"
                           placeholder="16 digit No KK">
                    @error('no_kk')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Data Orang Tua --}}
            <h5 class="mb-3 mt-4 text-primary"><i class="bi bi-people"></i> Data Orang Tua</h5>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="nama_ayah" class="form-label">Nama Ayah</label>
                    <input type="text"
                           class="form-control @error('nama_ayah') is-invalid @enderror"
                           id="nama_ayah" name="nama_ayah"
                           value="{{ old('nama_ayah', $anakYatim->nama_ayah) }}">
                    @error('nama_ayah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="status_ayah" class="form-label">Status Ayah</label>
                    <select class="form-select @error('status_ayah') is-invalid @enderror"
                            id="status_ayah" name="status_ayah">
                        <option value="">Pilih status</option>
                        <option value="Meninggal" {{ old('status_ayah', $anakYatim->status_ayah) == 'Meninggal' ? 'selected' : '' }}>Meninggal</option>
                        <option value="Tidak Diketahui" {{ old('status_ayah', $anakYatim->status_ayah) == 'Tidak Diketahui' ? 'selected' : '' }}>Tidak Diketahui</option>
                        <option value="Tidak Mampu" {{ old('status_ayah', $anakYatim->status_ayah) == 'Tidak Mampu' ? 'selected' : '' }}>Tidak Mampu</option>
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
                           id="nama_ibu" name="nama_ibu"
                           value="{{ old('nama_ibu', $anakYatim->nama_ibu) }}">
                    @error('nama_ibu')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="status_ibu" class="form-label">Status Ibu</label>
                    <select class="form-select @error('status_ibu') is-invalid @enderror"
                            id="status_ibu" name="status_ibu">
                        <option value="">Pilih status</option>
                        <option value="Hidup" {{ old('status_ibu', $anakYatim->status_ibu) == 'Hidup' ? 'selected' : '' }}>Hidup</option>
                        <option value="Meninggal" {{ old('status_ibu', $anakYatim->status_ibu) == 'Meninggal' ? 'selected' : '' }}>Meninggal</option>
                        <option value="Tidak Diketahui" {{ old('status_ibu', $anakYatim->status_ibu) == 'Tidak Diketahui' ? 'selected' : '' }}>Tidak Diketahui</option>
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
                       id="nomor_telepon_wali" name="nomor_telepon_wali"
                       value="{{ old('nomor_telepon_wali', $anakYatim->nomor_telepon_wali) }}"
                       placeholder="Contoh: 08123456789">
                <small class="form-text text-muted">Hanya boleh berisi angka, tanda + dan -</small>
                @error('nomor_telepon_wali')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Data Pendidikan --}}
            <h5 class="mb-3 mt-4 text-primary"><i class="bi bi-book"></i> Data Pendidikan</h5>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="pendidikan_terakhir" class="form-label">Pendidikan Terakhir</label>
                    <select class="form-select @error('pendidikan_terakhir') is-invalid @enderror"
                            id="pendidikan_terakhir" name="pendidikan_terakhir">
                        <option value="">Pilih pendidikan</option>
                        @foreach(['Belum Sekolah','TK','SD','SMP','SMA','Kuliah'] as $p)
                            <option value="{{ $p }}" {{ old('pendidikan_terakhir', $anakYatim->pendidikan_terakhir) == $p ? 'selected' : '' }}>{{ $p }}</option>
                        @endforeach
                    </select>
                    @error('pendidikan_terakhir')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="sekolah_saat_ini" class="form-label">Sekolah Saat Ini</label>
                    <input type="text"
                           class="form-control @error('sekolah_saat_ini') is-invalid @enderror"
                           id="sekolah_saat_ini" name="sekolah_saat_ini"
                           value="{{ old('sekolah_saat_ini', $anakYatim->sekolah_saat_ini) }}"
                           placeholder="Nama sekolah">
                    @error('sekolah_saat_ini')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Foto --}}
            <h5 class="mb-3 mt-4 text-primary"><i class="bi bi-camera"></i> Foto</h5>

            @if($anakYatim->foto)
            <div class="mb-3">
                <label class="form-label">Foto Saat Ini</label>
                <div>
                    <img src="{{ asset('storage/' . $anakYatim->foto) }}"
                         alt="Foto {{ $anakYatim->nama_lengkap }}"
                         class="img-thumbnail"
                         style="max-width:200px;max-height:200px;">
                </div>
                <small class="text-muted">Upload foto baru untuk mengganti</small>
            </div>
            @endif

            <div class="mb-3">
                <label for="foto" class="form-label">Upload Foto Baru (Opsional)</label>
                <input type="file"
                       class="form-control @error('foto') is-invalid @enderror"
                       id="foto" name="foto"
                       accept="image/jpeg,image/jpg,image/png"
                       onchange="previewImage(event)">
                <small class="text-muted">Format: JPG, PNG. Maksimal 2MB</small>
                @error('foto')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div id="photoPreview" class="mb-3" style="display:none;">
                <img id="previewImg" src="" alt="Preview"
                     class="img-thumbnail" style="max-width:200px;max-height:200px;">
            </div>

            {{-- Field wajib untuk validasi tapi tidak ditampilkan ke orang tua --}}
            <input type="hidden" name="tanggal_masuk" value="{{ $anakYatim->tanggal_masuk->format('Y-m-d') }}">

            <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
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
function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('photoPreview');
    const previewImg = document.getElementById('previewImg');
    if (file) {
        if (file.size > 2097152) {
            alert('Ukuran file terlalu besar! Maksimal 2MB');
            event.target.value = '';
            preview.style.display = 'none';
            return;
        }
        const reader = new FileReader();
        reader.onload = e => { previewImg.src = e.target.result; preview.style.display = 'block'; };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
}
</script>
@endpush
