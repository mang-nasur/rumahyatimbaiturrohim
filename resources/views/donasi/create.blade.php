<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Form Donasi – Rumah Yatim Baiturrohim</title>
  <link rel="icon" href="{{ asset('favicon.ico') }}" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --green: #1a7a4a;
      --green-dark: #145c38;
      --green-light: #e8f5ee;
      --gold: #d4a017;
      --white: #ffffff;
      --gray-100: #f8f9fa;
      --gray-200: #e9ecef;
      --gray-600: #6c757d;
      --gray-800: #343a40;
      --shadow: 0 4px 20px rgba(0,0,0,0.08);
      --shadow-md: 0 8px 30px rgba(0,0,0,0.12);
      --radius: 14px;
      --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    html { scroll-behavior: smooth; }

    body {
      font-family: 'Poppins', sans-serif;
      background:
        linear-gradient(135deg, rgba(13,74,40,0.88) 0%, rgba(26,122,74,0.82) 60%, rgba(46,204,113,0.75) 100%),
        url("{{ asset('images/hero-bg.jpg') }}") center center / cover no-repeat fixed;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
      padding: 30px 16px 60px;
    }

    /* === HEADER === */
    .page-header {
      text-align: center;
      margin-bottom: 32px;
      color: #fff;
    }

    .page-header a.back-link {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      color: rgba(255,255,255,0.8);
      text-decoration: none;
      font-size: 0.85rem;
      margin-bottom: 20px;
      transition: var(--transition);
    }

    .page-header a.back-link:hover {
      color: #fff;
    }

    .page-header .logo-wrap {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      margin-bottom: 10px;
    }

    .page-header .logo-box {
      width: 52px;
      height: 52px;
      background: rgba(255,255,255,0.15);
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }

    .page-header .logo-box img {
      width: 36px;
      height: 36px;
      object-fit: contain;
    }

    .page-header h1 {
      font-size: 1.6rem;
      font-weight: 800;
      color: #fff;
    }

    .page-header p {
      color: rgba(255,255,255,0.8);
      font-size: 0.9rem;
      margin-top: 6px;
    }

    /* === CARD === */
    .form-card {
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 20px 60px rgba(0,0,0,0.2);
      width: 100%;
      max-width: 560px;
      overflow: hidden;
    }

    .form-card-header {
      background: linear-gradient(135deg, var(--green-dark), var(--green));
      padding: 24px 32px;
      color: #fff;
    }

    .form-card-header h2 {
      font-size: 1.15rem;
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .form-card-header p {
      font-size: 0.82rem;
      color: rgba(255,255,255,0.8);
      margin-top: 4px;
    }

    .form-body {
      padding: 32px;
    }

    /* === ALERT === */
    .alert-error {
      background: #fef2f2;
      border: 1px solid #fecaca;
      border-radius: 10px;
      padding: 14px 18px;
      margin-bottom: 24px;
      color: #dc2626;
      font-size: 0.85rem;
    }

    .alert-error ul {
      margin: 6px 0 0 16px;
    }

    /* === FORM GROUPS === */
    .form-group {
      margin-bottom: 22px;
    }

    .form-group label {
      display: block;
      font-weight: 600;
      font-size: 0.88rem;
      color: var(--gray-800);
      margin-bottom: 8px;
    }

    .form-group label .required {
      color: #dc2626;
      margin-left: 2px;
    }

    .form-group label .hint {
      font-weight: 400;
      color: var(--gray-600);
      font-size: 0.78rem;
      margin-left: 6px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
      width: 100%;
      padding: 12px 16px;
      border: 2px solid var(--gray-200);
      border-radius: 10px;
      font-family: inherit;
      font-size: 0.9rem;
      color: var(--gray-800);
      transition: var(--transition);
      background: #fff;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
      outline: none;
      border-color: var(--green);
      box-shadow: 0 0 0 3px rgba(26, 122, 74, 0.1);
    }

    .form-group input.is-invalid,
    .form-group select.is-invalid,
    .form-group textarea.is-invalid {
      border-color: #dc2626;
    }

    .invalid-feedback {
      color: #dc2626;
      font-size: 0.78rem;
      margin-top: 5px;
      display: flex;
      align-items: center;
      gap: 4px;
    }

    .form-group textarea {
      min-height: 100px;
      resize: vertical;
    }

    /* === NOMINAL PRESETS === */
    .nominal-presets {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 8px;
      margin-bottom: 10px;
    }

    .preset-btn {
      padding: 8px 6px;
      border: 2px solid var(--gray-200);
      border-radius: 8px;
      background: #fff;
      font-family: inherit;
      font-size: 0.78rem;
      font-weight: 600;
      color: var(--gray-800);
      cursor: pointer;
      transition: var(--transition);
      text-align: center;
    }

    .preset-btn:hover,
    .preset-btn.active {
      border-color: var(--green);
      background: var(--green-light);
      color: var(--green);
    }

    /* === INPUT NOMINAL WRAPPER === */
    .input-prefix-wrap {
      position: relative;
      display: flex;
      align-items: center;
    }

    .input-prefix {
      position: absolute;
      left: 14px;
      font-weight: 600;
      color: var(--gray-600);
      font-size: 0.9rem;
      pointer-events: none;
    }

    .input-prefix-wrap input {
      padding-left: 42px;
    }

    /* === SUBMIT === */
    .btn-submit {
      width: 100%;
      background: linear-gradient(135deg, var(--green-dark), var(--green));
      color: #fff;
      padding: 15px 24px;
      border: none;
      border-radius: 10px;
      font-family: inherit;
      font-weight: 700;
      font-size: 1rem;
      cursor: pointer;
      transition: var(--transition);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      margin-top: 8px;
    }

    .btn-submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(26, 122, 74, 0.4);
    }

    .btn-submit:active {
      transform: translateY(0);
    }

    /* === FOOTER NOTE === */
    .form-footer-note {
      text-align: center;
      padding: 18px 32px 24px;
      border-top: 1px solid var(--gray-200);
      font-size: 0.78rem;
      color: var(--gray-600);
      line-height: 1.6;
    }

    .form-footer-note strong {
      color: var(--green);
    }

    /* === REKENING HINT === */
    .rek-hint {
      margin-top: 10px;
      padding: 10px 14px;
      border-radius: 8px;
      font-size: 0.8rem;
      background: var(--green-light);
      color: var(--green-dark);
      border: 1px solid #b7dfc8;
      line-height: 1.5;
    }

    .rek-hint-pembangunan {
      background: #fff8e1;
      color: #7a5200;
      border-color: #ffe082;
    }

    /* === RESPONSIVE === */
    @media (max-width: 600px) {
      body { padding: 20px 12px 50px; }
      .form-body { padding: 24px 20px; }
      .form-card-header { padding: 20px 24px; }
      .nominal-presets { grid-template-columns: repeat(2, 1fr); }
    }
  </style>
</head>
<body>

  <!-- Header -->
  <div class="page-header">
    <a href="{{ route('home') }}" class="back-link">
      ← Kembali ke Beranda
    </a>
    <div class="logo-wrap">
      <div class="logo-box">
        <img src="{{ asset('images/Logo-Yayasan.png') }}" alt="Logo Baiturrohim" />
      </div>
      <div>
        <h1>Rumah Yatim Baiturrohim</h1>
      </div>
    </div>
    <p>Setiap kebaikan Anda adalah cahaya bagi mereka 🤲</p>
  </div>

  <!-- Form Card -->
  <div class="form-card">
    <div class="form-card-header">
      <h2>💝 Form Donasi</h2>
      <p>Isi data donasi Anda dengan lengkap dan benar</p>
    </div>

    <div class="form-body">

      {{-- Error summary --}}
      @if($errors->any())
        <div class="alert-error">
          <strong>⚠️ Mohon perbaiki kesalahan berikut:</strong>
          <ul>
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('donasi.store') }}" method="POST" id="donasiForm">
        @csrf

        {{-- Nama Donatur --}}
        <div class="form-group">
          <label for="nama_donatur">
            Nama Donatur <span class="required">*</span>
            <span class="hint">(boleh nama samaran / Hamba Allah)</span>
          </label>
          <input
            type="text"
            id="nama_donatur"
            name="nama_donatur"
            value="{{ old('nama_donatur') }}"
            placeholder="Contoh: Budi Santoso / Hamba Allah"
            class="{{ $errors->has('nama_donatur') ? 'is-invalid' : '' }}"
            autocomplete="name"
            required
          />
          @error('nama_donatur')
            <div class="invalid-feedback">⚠ {{ $message }}</div>
          @enderror
        </div>

        {{-- Nominal --}}
        <div class="form-group">
          <label for="nominal">
            Nominal Donasi <span class="required">*</span>
          </label>
          <div class="nominal-presets">
            <button type="button" class="preset-btn" data-value="25000">Rp 25.000</button>
            <button type="button" class="preset-btn" data-value="50000">Rp 50.000</button>
            <button type="button" class="preset-btn" data-value="100000">Rp 100.000</button>
            <button type="button" class="preset-btn" data-value="250000">Rp 250.000</button>
            <button type="button" class="preset-btn" data-value="500000">Rp 500.000</button>
            <button type="button" class="preset-btn" data-value="1000000">Rp 1.000.000</button>
          </div>
          <div class="input-prefix-wrap">
            <span class="input-prefix">Rp</span>
            <input
              type="number"
              id="nominal"
              name="nominal"
              value="{{ old('nominal') }}"
              placeholder="Masukkan nominal (min. 10.000)"
              min="10000"
              step="1000"
              class="{{ $errors->has('nominal') ? 'is-invalid' : '' }}"
              required
            />
          </div>
          @error('nominal')
            <div class="invalid-feedback">⚠ {{ $message }}</div>
          @enderror
        </div>

        {{-- Ditujukan --}}
        <div class="form-group">
          <label for="ditujukan">
            Ditujukan Untuk <span class="required">*</span>
          </label>
          <select
            id="ditujukan"
            name="ditujukan"
            class="{{ $errors->has('ditujukan') ? 'is-invalid' : '' }}"
            required
          >
            <option value="" disabled {{ old('ditujukan') ? '' : 'selected' }}>-- Pilih tujuan donasi --</option>

            {{-- Kelompok: Operasional Yayasan --}}
            <optgroup label="🏠 Operasional Yayasan">
              <option value="Umum – Operasional Yayasan"     {{ old('ditujukan') == 'operasional|Umum – Operasional Yayasan'     ? 'selected' : '' }}>Umum – Operasional Yayasan</option>
            </optgroup>

            {{-- Kelompok: Pembangunan --}}
            <optgroup label="🏗️ Pembangunan">
              <option value="Pembangunan Gedung / Renovasi"  {{ old('ditujukan') == 'pembangunan|Pembangunan Gedung / Renovasi'  ? 'selected' : '' }}>Pembangunan Gedung / Renovasi</option>
            </optgroup>
          </select>
          @error('ditujukan')
            <div class="invalid-feedback">⚠ {{ $message }}</div>
          @enderror

          {{-- Info rekening berubah sesuai pilihan --}}
          <div id="rek-info-operasional" class="rek-hint" style="display:none;">
            🏦 Dana akan dikirim ke <strong>BSI 7294768763</strong> a.n. Rumah Yatim Baiturrohim YYS
          </div>
          <div id="rek-info-pembangunan" class="rek-hint rek-hint-pembangunan" style="display:none;">
            🏗️ Dana akan dikirim ke <strong>BSI 7318110233</strong> a.n. Pembangunan Baiturrohim
          </div>
        </div>

        {{-- Doa --}}
        <div class="form-group">
          <label for="doa">
            Doa / Pesan
            <span class="hint">(opsional)</span>
          </label>
          <textarea
            id="doa"
            name="doa"
            placeholder="Tuliskan doa atau pesan yang ingin Anda sampaikan..."
            class="{{ $errors->has('doa') ? 'is-invalid' : '' }}"
          >{{ old('doa') }}</textarea>
          @error('doa')
            <div class="invalid-feedback">⚠ {{ $message }}</div>
          @enderror
        </div>

        <button type="submit" class="btn-submit">
          <span>💝</span>
          <span>Lanjutkan ke Pembayaran</span>
          <span>→</span>
        </button>
      </form>
    </div>

    <div class="form-footer-note">
      🔒 Data Anda aman dan terlindungi.<br/>
      Rekening donasi disesuaikan otomatis berdasarkan tujuan yang Anda pilih.
    </div>
  </div>

  <script>
    // Preset nominal buttons
    const presetBtns = document.querySelectorAll('.preset-btn');
    const nominalInput = document.getElementById('nominal');

    presetBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        const val = btn.dataset.value;
        nominalInput.value = val;
        presetBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
      });
    });

    // Sync active state when user types manually
    nominalInput.addEventListener('input', () => {
      presetBtns.forEach(btn => {
        btn.classList.toggle('active', btn.dataset.value === nominalInput.value);
      });
    });

    // Restore active state on page load (old value)
    if (nominalInput.value) {
      presetBtns.forEach(btn => {
        if (btn.dataset.value === nominalInput.value) {
          btn.classList.add('active');
        }
      });
    }

    // Tampilkan info rekening sesuai kategori yang dipilih
    const ditujukanSelect = document.getElementById('ditujukan');
    const rekOperasional  = document.getElementById('rek-info-operasional');
    const rekPembangunan  = document.getElementById('rek-info-pembangunan');

    function updateRekeningHint() {
      const val = ditujukanSelect.value;
      if (!val) {
        rekOperasional.style.display = 'none';
        rekPembangunan.style.display = 'none';
        return;
      }
      const kategori = val.split('|')[0];
      rekOperasional.style.display = kategori === 'operasional'  ? 'block' : 'none';
      rekPembangunan.style.display = kategori === 'pembangunan'  ? 'block' : 'none';
    }

    ditujukanSelect.addEventListener('change', updateRekeningHint);
    // Jalankan saat load (untuk old value setelah validasi gagal)
    updateRekeningHint();
  </script>
</body>
</html>
