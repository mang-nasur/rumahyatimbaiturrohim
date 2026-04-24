@extends('layouts.app')

@section('title', 'Ubah Password')

@section('content')
<div class="mb-4">
    <h2><i class="bi bi-key"></i> Ubah Password</h2>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('profile.change-password.update') }}">
            @csrf

            <div class="mb-3">
                <label for="current_password" class="form-label">Password Saat Ini <span class="text-danger">*</span></label>
                <input type="password" 
                       class="form-control @error('current_password') is-invalid @enderror" 
                       id="current_password" 
                       name="current_password"
                       required>
                @error('current_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password Baru <span class="text-danger">*</span></label>
                <input type="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       id="password" 
                       name="password"
                       required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Password minimal 8 karakter</small>
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                <input type="password" 
                       class="form-control" 
                       id="password_confirmation" 
                       name="password_confirmation"
                       required>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Ubah Password
                </button>
                <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
