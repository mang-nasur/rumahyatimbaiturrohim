@extends('layouts.app')

@section('title', 'Edit Berita')

@push('styles')
<style>
    .isi-editor {
        min-height: 320px;
        border: 2px solid #dee2e6;
        border-radius: 8px;
        padding: 14px 16px;
        font-size: .93rem;
        line-height: 1.7;
        transition: border-color .2s;
        outline: none;
    }
    .isi-editor:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13,110,253,.1);
    }
    .toolbar {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        padding: 8px 10px;
        background: #f8f9fa;
        border: 2px solid #dee2e6;
        border-bottom: none;
        border-radius: 8px 8px 0 0;
    }
    .toolbar button {
        padding: 4px 10px;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        background: #fff;
        font-size: .82rem;
        cursor: pointer;
        transition: all .15s;
        color: #343a40;
    }
    .toolbar button:hover { background: #e9ecef; }
    .toolbar .sep { width: 1px; background: #dee2e6; margin: 0 4px; }
    .foto-preview-wrap { position: relative; display: inline-block; }
    .foto-preview-wrap img { border-radius: 10px; max-height: 200px; object-fit: cover; }
    .char-count { font-size: .75rem; color: #6c757d; text-align: right; margin-top: 3px; }
    .char-count.warn { color: #dc3545; }
</style>
@endpush

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('berita.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <div>
        <h4 class="mb-0 fw-bold"><i class="bi bi-pencil-square text-primary"></i> Edit Berita</h4>
        <small class="text-muted">{{ Str::limit($berita->judul, 60) }}</small>
    </div>
</div>

<form action="{{ route('berita.update', $berita) }}" method="POST" enctype="multipart/form-data" id="beritaForm">
    @csrf @method('PUT')

    <div class="row g-4">

        {{-- Kolom Kiri: Konten --}}
        <div class="col-lg-8">

            {{-- Judul --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <label class="form-label fw-semibold">
                        Judul Berita <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="judul" id="judul"
                           class="form-control form-control-lg @error('judul') is-invalid @enderror"
                           value="{{ old('judul', $berita->judul) }}"
                           maxlength="200" required>
                    <div class="char-count" id="judulCount">0 / 200</div>
                    @error('judul')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Ringkasan --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <label class="form-label fw-semibold">
                        Ringkasan
                        <span class="text-muted fw-normal small">(opsional)</span>
                    </label>
                    <textarea name="ringkasan" id="ringkasan"
                              class="form-control @error('ringkasan') is-invalid @enderror"
                              rows="2" maxlength="300">{{ old('ringkasan', $berita->ringkasan) }}</textarea>
                    <div class="char-count" id="ringkasanCount">0 / 300</div>
                    @error('ringkasan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Isi Berita --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <label class="form-label fw-semibold">
                        Isi Berita <span class="text-danger">*</span>
                    </label>
                    <div class="toolbar">
                        <button type="button" onclick="fmt('bold')"><b>B</b></button>
                        <button type="button" onclick="fmt('italic')"><i>I</i></button>
                        <button type="button" onclick="fmt('underline')"><u>U</u></button>
                        <div class="sep"></div>
                        <button type="button" onclick="fmt('insertUnorderedList')">• List</button>
                        <button type="button" onclick="fmt('insertOrderedList')">1. List</button>
                        <div class="sep"></div>
                        <button type="button" onclick="insertHeading()">H2</button>
                        <button type="button" onclick="fmt('formatBlock', 'p')">¶</button>
                        <div class="sep"></div>
                        <button type="button" onclick="fmt('justifyLeft')">⬅</button>
                        <button type="button" onclick="fmt('justifyCenter')">↔</button>
                        <button type="button" onclick="fmt('justifyRight')">➡</button>
                        <div class="sep"></div>
                        <button type="button" onclick="fmt('removeFormat')">✕ Format</button>
                    </div>
                    <div id="isiEditor"
                         class="isi-editor @error('isi') is-invalid @enderror"
                         contenteditable="true"
                         style="border-radius: 0 0 8px 8px;">
                        {!! old('isi', $berita->isi) !!}
                    </div>
                    <input type="hidden" name="isi" id="isiHidden">
                    @error('isi')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

        </div>

        {{-- Kolom Kanan: Meta --}}
        <div class="col-lg-4">

            {{-- Publikasi --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header fw-semibold bg-transparent">
                    <i class="bi bi-send text-primary"></i> Publikasi
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Status <span class="text-danger">*</span></label>
                        <select name="status" id="statusSelect"
                                class="form-select @error('status') is-invalid @enderror" required>
                            <option value="draft"     {{ old('status', $berita->status) == 'draft'     ? 'selected' : '' }}>📝 Draft</option>
                            <option value="published" {{ old('status', $berita->status) == 'published' ? 'selected' : '' }}>✅ Published</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Tanggal Publikasi</label>
                        <input type="date" name="tanggal_publikasi"
                               class="form-control @error('tanggal_publikasi') is-invalid @enderror"
                               value="{{ old('tanggal_publikasi', $berita->tanggal_publikasi?->format('Y-m-d')) }}">
                        @error('tanggal_publikasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary" id="btnSubmit">
                            <i class="bi bi-save"></i> Update Berita
                        </button>
                        @if($berita->status === 'published')
                        <a href="{{ route('berita.show', $berita->slug) }}"
                           class="btn btn-outline-info btn-sm" target="_blank">
                            <i class="bi bi-eye"></i> Lihat di Beranda
                        </a>
                        @endif
                        <a href="{{ route('berita.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </div>
            </div>

            {{-- Kategori --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header fw-semibold bg-transparent">
                    <i class="bi bi-tag text-primary"></i> Kategori
                </div>
                <div class="card-body">
                    <select name="kategori"
                            class="form-select @error('kategori') is-invalid @enderror" required>
                        @foreach(\App\Models\Berita::KATEGORI as $kat)
                            <option value="{{ $kat }}" {{ old('kategori', $berita->kategori) == $kat ? 'selected' : '' }}>
                                {{ $kat }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Foto --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header fw-semibold bg-transparent">
                    <i class="bi bi-image text-primary"></i> Foto Berita
                </div>
                <div class="card-body">
                    {{-- Foto existing --}}
                    @if($berita->foto)
                    <div class="mb-3">
                        <p class="small text-muted mb-1">Foto saat ini:</p>
                        <div class="foto-preview-wrap">
                            <img src="{{ $berita->foto_url }}" alt="Foto berita" class="w-100" id="fotoPreview">
                        </div>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="hapus_foto" id="hapusFoto" value="1">
                            <label class="form-check-label small text-danger" for="hapusFoto">
                                Hapus foto ini
                            </label>
                        </div>
                    </div>
                    @else
                    <div id="fotoPreviewWrap" class="mb-3" style="display:none;">
                        <img id="fotoPreviewNew" src="" alt="Preview" class="w-100" style="border-radius:10px;max-height:200px;object-fit:cover;">
                    </div>
                    @endif

                    <label class="form-label small fw-semibold">
                        {{ $berita->foto ? 'Ganti Foto' : 'Upload Foto' }}
                        <span class="text-muted fw-normal">(opsional)</span>
                    </label>
                    <input type="file" name="foto" id="foto"
                           class="form-control @error('foto') is-invalid @enderror"
                           accept="image/jpg,image/jpeg,image/png,image/webp">
                    <div class="form-text">JPG, PNG, WebP — maks. 2MB</div>
                    @error('foto')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Danger zone --}}
            <div class="card border-danger shadow-sm">
                <div class="card-body">
                    <p class="small text-muted mb-2">Hapus berita ini secara permanen.</p>
                    <form action="{{ route('berita.destroy', $berita) }}" method="POST"
                          onsubmit="return confirm('Yakin hapus berita ini? Tindakan tidak dapat dibatalkan.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                            <i class="bi bi-trash"></i> Hapus Berita
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    const editor = document.getElementById('isiEditor');
    const hidden = document.getElementById('isiHidden');

    function fmt(cmd, val = null) { editor.focus(); document.execCommand(cmd, false, val); }
    function insertHeading() { editor.focus(); document.execCommand('formatBlock', false, 'h2'); }

    document.getElementById('beritaForm').addEventListener('submit', function () {
        hidden.value = editor.innerHTML;
    });

    function updateCount(inputId, countId, max) {
        const el  = document.getElementById(inputId);
        const cnt = document.getElementById(countId);
        if (!el || !cnt) return;
        cnt.textContent = el.value.length + ' / ' + max;
        cnt.classList.toggle('warn', el.value.length > max * 0.9);
        el.addEventListener('input', () => {
            cnt.textContent = el.value.length + ' / ' + max;
            cnt.classList.toggle('warn', el.value.length > max * 0.9);
        });
    }
    updateCount('judul', 'judulCount', 200);
    updateCount('ringkasan', 'ringkasanCount', 300);

    // Preview foto baru
    document.getElementById('foto').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            const prev = document.getElementById('fotoPreview') || document.getElementById('fotoPreviewNew');
            if (prev) {
                prev.src = e.target.result;
                const wrap = document.getElementById('fotoPreviewWrap');
                if (wrap) wrap.style.display = 'block';
            }
        };
        reader.readAsDataURL(file);
    });
</script>
@endpush
