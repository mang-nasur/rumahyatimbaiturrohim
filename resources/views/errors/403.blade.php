@extends('layouts.app')

@section('title', 'Akses Ditolak')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-danger">
            <div class="card-header bg-danger text-white">
                <h4 class="mb-0">
                    <i class="bi bi-shield-exclamation"></i> Akses Ditolak
                </h4>
            </div>
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-shield-exclamation text-danger" style="font-size: 5rem;"></i>
                </div>
                
                <h2 class="mb-3">403 - Forbidden</h2>
                
                <p class="lead mb-4">
                    Maaf, Anda tidak memiliki akses ke halaman ini.
                </p>
                
                <p class="text-muted mb-4">
                    @if(isset($exception) && $exception->getMessage())
                        {{ $exception->getMessage() }}
                    @else
                        Halaman yang Anda coba akses memerlukan hak akses khusus. 
                        Silakan hubungi administrator jika Anda merasa ini adalah kesalahan.
                    @endif
                </p>

                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <a href="{{ url('/') }}" class="btn btn-primary">
                        <i class="bi bi-house-door"></i> Ke Dashboard
                    </a>
                </div>
            </div>
        </div>

        @auth
        <div class="card mt-3">
            <div class="card-body">
                <h6 class="card-title">Informasi Akun Anda:</h6>
                <ul class="list-unstyled mb-0">
                    <li><strong>Nama:</strong> {{ auth()->user()->name }}</li>
                    <li><strong>Email:</strong> {{ auth()->user()->email }}</li>
                    <li><strong>Role:</strong> <span class="badge bg-primary">{{ ucfirst(auth()->user()->role) }}</span></li>
                </ul>
            </div>
        </div>
        @endauth
    </div>
</div>
@endsection
