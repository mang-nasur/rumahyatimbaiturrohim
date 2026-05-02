<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Konfirmasi Donasi – Rumah Yatim Baiturrohim</title>
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
      --bsi-green: #00703c;
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
      margin-bottom: 28px;
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
      font-size: 1.5rem;
      font-weight: 800;
      color: #fff;
    }

    /* === SUCCESS BADGE === */
    .success-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: rgba(255,255,255,0.2);
      color: #fff;
      padding: 8px 20px;
      border-radius: 50px;
      font-size: 0.85rem;
      font-weight: 600;
      margin-bottom: 24px;
      backdrop-filter: blur(10px);
    }

    /* === MAIN CARD === */
    .conf-card {
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 20px 60px rgba(0,0,0,0.2);
      width: 100%;
      max-width: 580px;
      overflow: hidden;
    }

    /* === DONASI SUMMARY === */
    .summary-header {
      background: linear-gradient(135deg, var(--green-dark), var(--green));
      padding: 28px 32px;
      color: #fff;
    }

    .summary-header h2 {
      font-size: 1.1rem;
      font-weight: 700;
      margin-bottom: 16px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .summary-rows {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .summary-row {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 12px;
      font-size: 0.88rem;
    }

    .summary-row .label {
      color: rgba(255,255,255,0.75);
      flex-shrink: 0;
      min-width: 120px;
    }

    .summary-row .value {
      color: #fff;
      font-weight: 600;
      text-align: right;
    }

    .summary-row.nominal-row .value {
      font-size: 1.1rem;
      color: var(--gold);
    }

    .summary-row .doa-text {
      font-style: italic;
      color: rgba(255,255,255,0.85);
      font-weight: 400;
      font-size: 0.82rem;
      line-height: 1.5;
    }

    /* === PAYMENT SECTION === */
    .payment-section {
      padding: 28px 32px;
    }

    .payment-section h3 {
      font-size: 1rem;
      font-weight: 700;
      color: var(--gray-800);
      margin-bottom: 18px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    /* === BSI CARD === */
    .bsi-card {
      background: linear-gradient(135deg, #00703c 0%, #009a52 100%);
      border-radius: 16px;
      padding: 24px 28px;
      color: #fff;
      position: relative;
      overflow: hidden;
      margin-bottom: 20px;
    }

    .bsi-card::before {
      content: '';
      position: absolute;
      top: -30px;
      right: -30px;
      width: 120px;
      height: 120px;
      background: rgba(255,255,255,0.06);
      border-radius: 50%;
    }

    .bsi-card::after {
      content: '';
      position: absolute;
      bottom: -40px;
      right: 40px;
      width: 160px;
      height: 160px;
      background: rgba(255,255,255,0.04);
      border-radius: 50%;
    }

    .bsi-logo-row {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 18px;
    }

    .bsi-logo-icon {
      width: 44px;
      height: 44px;
      background: rgba(255,255,255,0.2);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 22px;
      font-weight: 800;
      color: #fff;
      font-family: 'Poppins', sans-serif;
    }

    .bsi-bank-name {
      font-size: 1rem;
      font-weight: 700;
      color: #fff;
    }

    .bsi-bank-sub {
      font-size: 0.75rem;
      color: rgba(255,255,255,0.75);
    }

    .bsi-label {
      font-size: 0.75rem;
      color: rgba(255,255,255,0.7);
      margin-bottom: 4px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .bsi-number {
      font-size: 1.8rem;
      font-weight: 800;
      color: #fff;
      letter-spacing: 2px;
      font-family: 'Courier New', monospace;
      margin-bottom: 4px;
    }

    .bsi-an {
      font-size: 0.82rem;
      color: rgba(255,255,255,0.8);
      margin-bottom: 20px;
    }

    .bsi-divider {
      border: none;
      border-top: 1px solid rgba(255,255,255,0.2);
      margin: 16px 0;
    }

    .bsi-transfer-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .bsi-transfer-label {
      font-size: 0.8rem;
      color: rgba(255,255,255,0.75);
    }

    .bsi-transfer-amount {
      font-size: 1.3rem;
      font-weight: 800;
      color: #fff;
    }

    .bsi-transfer-note {
      font-size: 0.72rem;
      color: rgba(255,255,255,0.65);
      margin-top: 4px;
      text-align: right;
    }

    /* === COPY BUTTON === */
    .copy-row {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-top: 12px;
    }

    .copy-btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(255,255,255,0.2);
      color: #fff;
      border: 1px solid rgba(255,255,255,0.3);
      border-radius: 8px;
      padding: 7px 14px;
      font-family: inherit;
      font-size: 0.78rem;
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
    }

    .copy-btn:hover {
      background: rgba(255,255,255,0.3);
    }

    .copy-btn.copied {
      background: rgba(255,255,255,0.35);
    }

    /* === STEPS === */
    .steps-list {
      list-style: none;
      margin-bottom: 24px;
    }

    .steps-list li {
      display: flex;
      align-items: flex-start;
      gap: 12px;
      padding: 10px 0;
      border-bottom: 1px solid var(--gray-200);
      font-size: 0.88rem;
      color: var(--gray-800);
      line-height: 1.5;
    }

    .steps-list li:last-child {
      border-bottom: none;
    }

    .step-num {
      width: 26px;
      height: 26px;
      background: var(--green-light);
      color: var(--green);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.75rem;
      font-weight: 700;
      flex-shrink: 0;
      margin-top: 1px;
    }

    /* === INFO BOX === */
    .info-box {
      background: #fffbeb;
      border: 1px solid #fde68a;
      border-radius: 10px;
      padding: 14px 18px;
      font-size: 0.82rem;
      color: #92400e;
      line-height: 1.6;
      margin-bottom: 24px;
    }

    .info-box strong {
      color: #78350f;
    }

    /* === ACTIONS === */
    .action-buttons {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .btn-done {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      background: linear-gradient(135deg, var(--green-dark), var(--green));
      color: #fff;
      padding: 14px 24px;
      border: none;
      border-radius: 10px;
      font-family: inherit;
      font-weight: 700;
      font-size: 0.95rem;
      cursor: pointer;
      text-decoration: none;
      transition: var(--transition);
    }

    .btn-done:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(26, 122, 74, 0.4);
    }

    .btn-back {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      background: transparent;
      color: var(--gray-600);
      padding: 12px 24px;
      border: 2px solid var(--gray-200);
      border-radius: 10px;
      font-family: inherit;
      font-weight: 600;
      font-size: 0.88rem;
      cursor: pointer;
      text-decoration: none;
      transition: var(--transition);
    }

    .btn-back:hover {
      border-color: var(--gray-600);
      color: var(--gray-800);
    }

    /* === FOOTER NOTE === */
    .conf-footer {
      text-align: center;
      padding: 16px 32px 24px;
      border-top: 1px solid var(--gray-200);
      font-size: 0.75rem;
      color: var(--gray-600);
      line-height: 1.6;
    }

    /* === RESPONSIVE === */
    @media (max-width: 600px) {
      body { padding: 20px 12px 50px; }
      .payment-section { padding: 22px 20px; }
      .summary-header { padding: 22px 20px; }
      .bsi-number { font-size: 1.4rem; letter-spacing: 1px; }
      .bsi-card { padding: 20px 20px; }
    }
  </style>
</head>
<body>

  <!-- Header -->
  <div class="page-header">
    <div class="logo-wrap">
      <div class="logo-box">
        <img src="{{ asset('images/Logo-Yayasan.png') }}" alt="Logo Baiturrohim" />
      </div>
      <div>
        <h1>Rumah Yatim Baiturrohim</h1>
      </div>
    </div>
    <div class="success-badge">
      ✅ Data donasi berhasil diterima
    </div>
  </div>

  <!-- Confirmation Card -->
  <div class="conf-card">

    <!-- Summary -->
    <div class="summary-header">
      <h2>📋 Ringkasan Donasi</h2>
      <div class="summary-rows">
        <div class="summary-row">
          <span class="label">Nama Donatur</span>
          <span class="value">{{ $data['nama_donatur'] }}</span>
        </div>
        <div class="summary-row nominal-row">
          <span class="label">Nominal Donasi</span>
          <span class="value">Rp {{ number_format($data['nominal'], 0, ',', '.') }}</span>
        </div>
        <div class="summary-row">
          <span class="label">Kategori</span>
          <span class="value">
            @if($data['kategori_donasi'] === 'pembangunan')
              🏗️ Pembangunan
            @else
              🏠 Operasional Yayasan
            @endif
          </span>
        </div>
        <div class="summary-row">
          <span class="label">Ditujukan Untuk</span>
          <span class="value">{{ $data['ditujukan'] }}</span>
        </div>
        @if($data['doa'])
        <div class="summary-row">
          <span class="label">Doa / Pesan</span>
          <span class="value doa-text">"{{ $data['doa'] }}"</span>
        </div>
        @endif
        <div class="summary-row">
          <span class="label">Waktu</span>
          <span class="value">{{ $data['created_at'] }}</span>
        </div>
      </div>
    </div>

    <!-- Payment Info -->
    <div class="payment-section">

      <h3>🏦 Informasi Pembayaran</h3>

      <!-- BSI Virtual Account Card -->
      <div class="bsi-card" style="{{ $data['kategori_donasi'] === 'pembangunan' ? 'background: linear-gradient(135deg, #7a5200 0%, #b87a00 100%);' : '' }}">
        <div class="bsi-logo-row">
          <div class="bsi-logo-icon">BSI</div>
          <div>
            <div class="bsi-bank-name">{{ $data['rekening']['bank'] }}</div>
            <div class="bsi-bank-sub">
              @if($data['kategori_donasi'] === 'pembangunan')
                Rekening Dana Pembangunan
              @else
                Rekening Donasi Resmi
              @endif
            </div>
          </div>
        </div>

        <div class="bsi-label">Nomor Rekening</div>
        <div class="bsi-number" id="nomorRekening">{{ $data['rekening']['nomor'] }}</div>
        <div class="bsi-an">a.n. {{ $data['rekening']['atas_nama'] }}</div>

        <hr class="bsi-divider" />

        <div class="bsi-transfer-row">
          <div>
            <div class="bsi-transfer-label">Jumlah Transfer</div>
            <div class="bsi-transfer-amount" id="nominalTransfer">
              Rp {{ number_format($nominalTransfer, 0, ',', '.') }}
            </div>
          </div>
          <div style="text-align:right;">
            <div class="bsi-transfer-note">Nominal donasi</div>
            <div class="bsi-transfer-note">+ kode unik <strong style="color:#fff;">{{ $data['kode_unik'] }}</strong></div>
          </div>
        </div>

        <div class="copy-row">
          <button class="copy-btn" id="copyNoRek" onclick="copyText('{{ $data['rekening']['nomor'] }}', 'copyNoRek', 'Nomor rekening disalin!')">
            📋 Salin No. Rekening
          </button>
          <button class="copy-btn" id="copyNominal" onclick="copyText('{{ $nominalTransfer }}', 'copyNominal', 'Nominal disalin!')">
            📋 Salin Nominal
          </button>
        </div>
      </div>

      <!-- Steps -->
      <h3>📱 Cara Transfer</h3>
      <ul class="steps-list">
        <li>
          <span class="step-num">1</span>
          <span>Buka aplikasi <strong>BSI Mobile</strong>, <strong>m-Banking</strong>, atau datang langsung ke <strong>ATM / Teller BSI</strong>.</span>
        </li>
        <li>
          <span class="step-num">2</span>
          <span>Pilih menu <strong>Transfer</strong> → <strong>Ke Rekening BSI</strong>.</span>
        </li>
        <li>
          <span class="step-num">3</span>
          <span>Masukkan nomor rekening <strong>{{ $data['rekening']['nomor'] }}</strong> (a.n. {{ $data['rekening']['atas_nama'] }}).</span>
        </li>
        <li>
          <span class="step-num">4</span>
          <span>Masukkan nominal transfer persis <strong>Rp {{ number_format($nominalTransfer, 0, ',', '.') }}</strong> (termasuk kode unik <strong>{{ $data['kode_unik'] }}</strong> untuk identifikasi donasi Anda).</span>
        </li>
        <li>
          <span class="step-num">5</span>
          <span>Pada kolom <strong>Berita / Keterangan</strong>, tuliskan: <strong>DONASI {{ strtoupper($data['nama_donatur']) }}</strong>.</span>
        </li>
        <li>
          <span class="step-num">6</span>
          <span>Konfirmasi dan selesaikan transfer. Simpan bukti transfer Anda.</span>
        </li>
      </ul>

      <!-- Info Box -->
      <div class="info-box">
        ⚠️ <strong>Penting:</strong> Transfer dengan nominal <strong>Rp {{ number_format($nominalTransfer, 0, ',', '.') }}</strong> (bukan Rp {{ number_format($data['nominal'], 0, ',', '.') }}) agar donasi Anda dapat teridentifikasi secara otomatis. Kode unik <strong>{{ $data['kode_unik'] }}</strong> akan dikembalikan atau disedekahkan sesuai kebijakan yayasan.
      </div>

      <!-- Action Buttons -->
      <div class="action-buttons">
        <form action="{{ route('donasi.selesai') }}" method="POST">
          @csrf
          <button type="submit" class="btn-done" style="width:100%;">
            ✅ Saya Sudah Transfer – Selesai
          </button>
        </form>
        <a href="{{ route('donasi.create') }}" class="btn-back">
          ← Donasi Lagi
        </a>
      </div>
    </div>

    <div class="conf-footer">
      Jazakumullahu khairan atas kepedulian Anda 🤲<br/>
      Semoga menjadi amal jariyah yang terus mengalir berkahnya.
    </div>
  </div>

  <script>
    function copyText(text, btnId, msg) {
      navigator.clipboard.writeText(text).then(() => {
        const btn = document.getElementById(btnId);
        const original = btn.innerHTML;
        btn.innerHTML = '✅ ' + msg;
        btn.classList.add('copied');
        setTimeout(() => {
          btn.innerHTML = original;
          btn.classList.remove('copied');
        }, 2000);
      }).catch(() => {
        // Fallback for older browsers
        const el = document.createElement('textarea');
        el.value = text;
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
        const btn = document.getElementById(btnId);
        const original = btn.innerHTML;
        btn.innerHTML = '✅ ' + msg;
        setTimeout(() => { btn.innerHTML = original; }, 2000);
      });
    }
  </script>
</body>
</html>
