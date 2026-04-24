@extends('layouts.app')

@section('title', 'Reset Password User')

@section('content')
<div class="mb-4">
    <h2><i class="bi bi-key"></i> Reset Password User</h2>
</div>

<div class="card">
    <div class="card-body">
        <div class="alert alert-warning mb-4">
            <i class="bi bi-exclamation-triangle"></i> Anda akan mereset password untuk user: <strong>{{ $user->name }}</strong> ({{ $user->email }})
        </div>

        <form method="POST" action="{{ route('users.reset-password.update', $user) }}">
            @csrf

            <div class="mb-3">
                <label for="password" class="form-label">Password Baru <span class="text-danger">*</span></label>
                <input type="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       id="password" 
                       name="password" 
                       required>
                <div class="form-text">Password minimal 8 karakter</div>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                <input type="password" 
                       class="form-control @error('password_confirmation') is-invalid @enderror" 
                       id="password_confirmation" 
                       name="password_confirmation" 
                       required>
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-key"></i> Reset Password
                </button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
