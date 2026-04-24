@extends('layouts.app')

@section('title', 'Selamat Datang')

@section('content')
<script>window.location.href = "{{ route('home') }}";</script>
@endsection

<div class="row mt-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-people-fill text-primary" style="font-size: 3rem;"></i>
                <h5 class="card-title mt-3">Data Anak Yatim</h5>
                <p class="card-text">Kelola data lengkap anak yatim dengan mudah</p>
                <a href="{{ url('/anak-yatim') }}" class="btn btn-outline-primary">Kelola Data</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-search text-success" style="font-size: 3rem;"></i>
                <h5 class="card-title mt-3">Pencarian & Filter</h5>
                <p class="card-text">Cari dan filter data berdasarkan kriteria</p>
                <a href="{{ url('/anak-yatim') }}" class="btn btn-outline-success">Cari Data</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-file-earmark-text text-info" style="font-size: 3rem;"></i>
                <h5 class="card-title mt-3">Laporan</h5>
                <p class="card-text">Buat laporan PDF dan Excel dengan mudah</p>
                <a href="{{ url('/laporan') }}" class="btn btn-outline-info">Buat Laporan</a>
            </div>
        </div>
    </div>
</div>
@endsection
