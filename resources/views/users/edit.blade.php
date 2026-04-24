@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="mb-4">
    <h2><i class="bi bi-pencil-square"></i> Edit User</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Manajemen User</a></li>
            <li class="breadcrumb-item active">Edit: {{ $user->name }}</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('users.update', $user) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Nama <span class="text-danger">*</span></label>
                <input type="text"
                       class="form-control @error('name') is-invalid @enderror"
                       id="name" name="name"
                       value="{{ old('name', $user->name) }}"
                       required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email"
                       class="form-control @error('email') is-invalid @enderror"
                       id="email" name="email"
                       value="{{ old('email', $user->email) }}"
                       required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                <select class="form-select @error('role') is-invalid @enderror"
                        id="role" name="role" required>
                    <option value="">Pilih Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role }}" {{ old('role', $user->role) == $role ? 'selected' : '' }}>
                            @if($role === 'orang_tua') Orang Tua / Wali
                            @else {{ ucfirst($role) }}
                            @endif
                        </option>
                    @endforeach
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Field anak yatim — tampil jika role orang_tua --}}
            <div class="mb-3" id="fieldAnakYatim"
                 style="{{ old('role', $user->role) === 'orang_tua' ? '' : 'display:none;' }}">
                <label for="anak_yatim_id" class="form-label">
                    Anak Yatim yang Diwakili
                    <span class="text-danger" id="starAnakYatim">*</span>
                </label>
                @if($anakYatimList)
                    <select class="form-select @error('anak_yatim_id') is-invalid @enderror"
                            id="anak_yatim_id" name="anak_yatim_id">
                        <option value="">-- Pilih anak yatim --</option>
                        @foreach($anakYatimList as $anak)
                            <option value="{{ $anak->id }}"
                                {{ old('anak_yatim_id', $user->anak_yatim_id) == $anak->id ? 'selected' : '' }}>
                                {{ $anak->nama_lengkap }}
                                @if($anak->nama_ibu) — Ibu: {{ $anak->nama_ibu }} @endif
                            </option>
                        @endforeach
                    </select>
                @else
                    <select class="form-select" id="anak_yatim_id" name="anak_yatim_id">
                        <option value="{{ $user->anak_yatim_id }}">
                            {{ $user->anakYatim?->nama_lengkap ?? '-' }}
                        </option>
                    </select>
                @endif
                @error('anak_yatim_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> Password tidak akan diubah. Untuk mengubah password, gunakan fitur reset password.
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan
                </button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const roleSelect = document.getElementById('role');
    const fieldAnak  = document.getElementById('fieldAnakYatim');
    const anakSelect = document.getElementById('anak_yatim_id');

    roleSelect.addEventListener('change', function () {
        if (this.value === 'orang_tua') {
            fieldAnak.style.display = '';
            if (anakSelect) anakSelect.setAttribute('required', 'required');
        } else {
            fieldAnak.style.display = 'none';
            if (anakSelect) {
                anakSelect.removeAttribute('required');
                anakSelect.value = '';
            }
        }
    });
</script>
@endpush
