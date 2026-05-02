@extends('layouts.app')

@section('title', 'Tambah Berita')

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
    .isi-editor.is-invalid { border-color: #dc3545; }

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
    .foto-preview-wrap .remove-foto {
        position: absolute; top: -8px; right: -8px;
        width: 24px; height: 24px; border-radius: 50%;
        background: #dc3545; color: #fff; border: none;
        font-size: .75rem; cursor: pointer; display: flex;
        align-items: center; justify-content: center;
    }

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
        <h4 class="mb-0 fw-bold"><i class="bi bi-plus-circle text-primary"></i> Tambah Berita</h4>
        <small class="text-muted">Buat berita atau kegiatan baru untuk ditampilkan di beranda</small>
    </div>
</div>

<form action="{{ route('berita.store') }}" method="POST" enctype="multipart/form-data" id="beritaForm">
    @csrf

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
                           value="{{ old('judul') }}"
                           placeholder="Tulis judul berita yang menarik..."
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
                        <span class="text-muted fw-normal small">(opsional — tampil di kartu berita beranda)</span>
                    </label>
                    <textarea name="ringkasan" id="ringkasan"
                              class="form-control @error('ringkasan') is-invalid @enderror"
                              rows="2" maxlength="300"
                              placeholder="Tulis ringkasan singkat berita (maks. 300 karakter)...">{{ old('ringkasan') }}</textarea>
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

                    {{-- Toolbar --}}
                    <div class="toolbar" id="toolbar">
                        <button type="button" onclick="fmt('bold')" title="Bold"><b>B</b></button>
                        <button type="button" onclick="fmt('italic')" title="Italic"><i>I</i></button>
                        <button type="button" onclick="fmt('underline')" title="Underline"><u>U</u></button>
                        <div class="sep"></div>
                        <button type="button" onclick="fmt('insertUnorderedList')" title="Bullet list">• List</button>
                        <button type="button" onclick="fmt('insertOrderedList')" title="Numbered list">1. List</button>
                        <div class="sep"></div>
                        <button type="button" onclick="insertHeading()" title="Heading">H2</button>
                        <button type="button" onclick="fmt('formatBlock', 'p')" title="Paragraf">¶</button>
                        <div class="sep"></div>
                        <button type="button" onclick="fmt('justifyLeft')" title="Rata kiri">⬅</button>
                        <button type="button" onclick="fmt('justifyCenter')" title="Tengah">↔</button>
                        <button type="button" onclick="fmt('justifyRight')" title="Rata kanan">➡</button>
                        <div class="sep"></div>
                        <button type="button" onclick="fmt('removeFormat')" title="Hapus format">✕ Format</button>
                    </div>

                    <div id="isiEditor"
                         class="isi-editor @error('isi') is-invalid @enderror"
                         contenteditable="true"
                         style="border-radius: 0 0 8px 8px;"
                         data-placeholder="Tulis isi berita di sini...">
                        {!! old('isi') !!}
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
                            <option value="draft"     {{ old('status', 'draft') == 'draft'     ? 'selected' : '' }}>📝 Draft</option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>✅ Published</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3" id="tglWrap">
                        <label class="form-label fw-semibold small">Tanggal Publikasi</label>
                        <input type="date" name="tanggal_publikasi"
                               class="form-control @error('tanggal_publikasi') is-invalid @enderror"
                               value="{{ old('tanggal_publikasi', date('Y-m-d')) }}">
                        <div class="form-text">Kosongkan untuk menggunakan tanggal hari ini.</div>
                        @error('tanggal_publikasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary" id="btnSubmit">
                            <i class="bi bi-save"></i> Simpan Berita
                        </button>
                        <a href="{{ route('berita.index') }}" class="btn btn-outline-secondary">
                            Batal
                        </a>
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
                            <option value="{{ $kat }}" {{ old('kategori', 'Umum') == $kat ? 'selected' : '' }}>
                                {{ $kat }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Foto --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header fw-semibold bg-transparent">
                    <i class="bi bi-image text-primary"></i> Foto Berita
                </div>
                <div class="card-body">
                    <div id="fotoPreviewWrap" class="mb-3" style="display:none;">
                        <div class="foto-preview-wrap">
                            <img id="fotoPreview" src="" alt="Preview" class="w-100">
                            <button type="button" class="remove-foto" onclick="removeFoto()" title="Hapus foto">✕</button>
                        </div>
                    </div>

                    <label for="foto" class="form-label small fw-semibold">
                        Upload Foto <span class="text-muted fw-normal">(opsional)</span>
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

        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    // ── Rich text editor ──────────────────────────────────────────────────────
    const editor = document.getElementById('isiEditor');
    const hidden = document.getElementById('isiHidden');

    function fmt(cmd, val = null) {
        editor.focus();
        document.execCommand(cmd, false, val);
    }

    function insertHeading() {
        editor.focus();
        document.execCommand('formatBlock', false, 'h2');
    }

    // Placeholder behaviour
    editor.addEventListener('focus', () => {
        if (editor.innerHTML === '') editor.innerHTML = '';
    });

    // Sync ke hidden input sebelum submit
    document.getElementById('beritaForm').addEventListener('submit', function () {
        hidden.value = editor.innerHTML;
    });

    // ── Char counters ─────────────────────────────────────────────────────────
    function updateCount(inputId, countId, max) {
        const el  = document.getElementById(inputId);
        const cnt = document.getElementById(countId);
        if (!el || !cnt) return;
        const len = el.value.length;
        cnt.textContent = len + ' / ' + max;
        cnt.classList.toggle('warn', len > max * 0.9);
        el.addEventListener('input', () => {
            cnt.textContent = el.value.length + ' / ' + max;
            cnt.classList.toggle('warn', el.value.length > max * 0.9);
        });
    }
    updateCount('judul', 'judulCount', 200);
    updateCount('ringkasan', 'ringkasanCount', 300);

    // ── Foto preview ──────────────────────────────────────────────────────────
    document.getElementById('foto').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('fotoPreview').src = e.target.result;
            document.getElementById('fotoPreviewWrap').style.display = 'block';
        };
        reader.readAsDataURL(file);
    });

    function removeFoto() {
        document.getElementById('foto').value = '';
        document.getElementById('fotoPreviewWrap').style.display = 'none';
        document.getElementById('fotoPreview').src = '';
    }

    // ── Status toggle ─────────────────────────────────────────────────────────
    const statusSel = document.getElementById('statusSelect');
    const btnSubmit = document.getElementById('btnSubmit');
    statusSel.addEventListener('change', function () {
        btnSubmit.innerHTML = this.value === 'published'
            ? '<i class="bi bi-send"></i> Publish Berita'
            : '<i class="bi bi-save"></i> Simpan Draft';
    });
    // Trigger on load
    statusSel.dispatchEvent(new Event('change'));
</script>
@endpush
