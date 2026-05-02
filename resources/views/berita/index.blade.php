@extends('layouts.app')

@section('title', 'Manajemen Berita')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold"><i class="bi bi-newspaper text-primary"></i> Manajemen Berita</h4>
        <small class="text-muted">Kelola berita dan kegiatan yang tampil di beranda</small>
    </div>
    <a href="{{ route('berita.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Berita
    </a>
</div>

{{-- Filter --}}
<div class="card shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('berita.index') }}" class="row g-2 align-items-end">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Cari judul berita..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">-- Semua Status --</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft"     {{ request('status') == 'draft'     ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="kategori" class="form-select form-select-sm">
                    <option value="">-- Semua Kategori --</option>
                    @foreach(\App\Models\Berita::KATEGORI as $kat)
                        <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-fill">
                    <i class="bi bi-search"></i> Cari
                </button>
                <a href="{{ route('berita.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-x"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Tabel --}}
<div class="card shadow-sm">
    <div class="card-body p-0">
        @if($beritaList->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-newspaper" style="font-size:3rem;opacity:.3;"></i>
                <p class="mt-3">Belum ada berita. <a href="{{ route('berita.create') }}">Tambah sekarang</a>.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:60px;">#</th>
                            <th>Judul</th>
                            <th style="width:120px;">Kategori</th>
                            <th style="width:110px;">Status</th>
                            <th style="width:130px;">Tgl Publikasi</th>
                            <th style="width:100px;">Penulis</th>
                            <th style="width:130px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($beritaList as $i => $b)
                        <tr>
                            <td class="text-muted small">{{ $beritaList->firstItem() + $i }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if($b->foto)
                                        <img src="{{ $b->foto_url }}" alt=""
                                             style="width:44px;height:44px;object-fit:cover;border-radius:6px;flex-shrink:0;">
                                    @else
                                        <div style="width:44px;height:44px;background:#e8f5ee;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0;">📰</div>
                                    @endif
                                    <div>
                                        <div class="fw-semibold" style="font-size:.9rem;">{{ Str::limit($b->judul, 60) }}</div>
                                        <div class="text-muted" style="font-size:.75rem;">{{ Str::limit($b->ringkasan_auto, 80) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary border">{{ $b->kategori }}</span>
                            </td>
                            <td>
                                @if($b->status === 'published')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                                        <i class="bi bi-check-circle-fill"></i> Published
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                        <i class="bi bi-pencil-fill"></i> Draft
                                    </span>
                                @endif
                            </td>
                            <td class="small text-muted">{{ $b->tanggal_format }}</td>
                            <td class="small text-muted">{{ $b->penulis?->name ?? '-' }}</td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    @if($b->status === 'published')
                                    <a href="{{ route('berita.show', $b->slug) }}"
                                       class="btn btn-sm btn-outline-info" title="Lihat di beranda" target="_blank">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @endif
                                    <a href="{{ route('berita.edit', $b) }}"
                                       class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('berita.destroy', $b) }}" method="POST"
                                          onsubmit="return confirm('Hapus berita ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($beritaList->hasPages())
                <div class="px-3 py-3 border-top">
                    {{ $beritaList->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
