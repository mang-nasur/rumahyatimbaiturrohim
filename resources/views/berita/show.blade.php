<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ $berita->judul }} – Rumah Yatim Baiturrohim</title>
  <meta name="description" content="{{ $berita->ringkasan_auto }}" />
  <link rel="icon" href="{{ asset('favicon.ico') }}" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --green: #1a7a4a; --green-dark: #145c38; --green-light: #e8f5ee;
      --gold: #d4a017; --gray-100: #f8f9fa; --gray-200: #e9ecef;
      --gray-600: #6c757d; --gray-800: #343a40; --text: #2d2d2d;
      --shadow: 0 4px 20px rgba(0,0,0,0.08);
      --radius: 12px; --transition: all 0.3s ease;
    }
    html { scroll-behavior: smooth; }
    body { font-family: 'Poppins', sans-serif; color: var(--text); background: var(--gray-100); }

    /* NAV */
    nav {
      background: rgba(255,255,255,0.97); backdrop-filter: blur(10px);
      box-shadow: 0 2px 15px rgba(0,0,0,0.08);
      padding: 0 5%; display: flex; align-items: center;
      justify-content: space-between; height: 64px; position: sticky; top: 0; z-index: 100;
    }
    .nav-brand { display: flex; align-items: center; gap: 10px; text-decoration: none; }
    .nav-logo { width: 38px; height: 38px; background: var(--green); border-radius: 8px;
      display: flex; align-items: center; justify-content: center; overflow: hidden; }
    .nav-logo img { width: 26px; height: 26px; object-fit: contain; }
    .nav-title { font-weight: 700; color: var(--green); font-size: 0.9rem; line-height: 1.2; }
    .nav-title span { display: block; font-weight: 400; font-size: 0.68rem; color: var(--gray-600); }
    .nav-back { display: inline-flex; align-items: center; gap: 6px; color: var(--green);
      text-decoration: none; font-size: 0.85rem; font-weight: 600; transition: var(--transition); }
    .nav-back:hover { color: var(--green-dark); }

    /* HERO */
    .article-hero {
      background: linear-gradient(135deg, var(--green-dark), var(--green));
      padding: 48px 5% 0;
    }
    .article-hero-inner { max-width: 860px; margin: 0 auto; }
    .kategori-badge {
      display: inline-block; background: rgba(255,255,255,0.2); color: #fff;
      padding: 4px 14px; border-radius: 50px; font-size: 0.75rem; font-weight: 600;
      margin-bottom: 14px;
    }
    .article-hero h1 {
      font-size: clamp(1.5rem, 3.5vw, 2.4rem); font-weight: 800; color: #fff;
      line-height: 1.3; margin-bottom: 16px;
    }
    .article-meta { display: flex; align-items: center; gap: 20px; flex-wrap: wrap;
      color: rgba(255,255,255,0.8); font-size: 0.82rem; padding-bottom: 28px; }
    .article-meta span { display: flex; align-items: center; gap: 5px; }

    /* FOTO HERO */
    .article-foto {
      max-width: 860px; margin: 0 auto;
      transform: translateY(28px);
    }
    .article-foto img {
      width: 100%; border-radius: 16px;
      box-shadow: 0 12px 40px rgba(0,0,0,0.2);
      max-height: 460px; object-fit: cover;
    }

    /* CONTENT */
    .article-wrap { max-width: 860px; margin: 0 auto; padding: 0 5% 60px; }
    .article-body {
      background: #fff; border-radius: 16px;
      padding: 40px 44px;
      box-shadow: var(--shadow);
      margin-top: 44px;
      line-height: 1.85;
      font-size: 0.97rem;
      color: var(--text);
    }
    .article-body.no-foto { margin-top: 44px; }
    .article-body h2 { font-size: 1.3rem; font-weight: 700; color: var(--gray-800); margin: 28px 0 12px; }
    .article-body h3 { font-size: 1.1rem; font-weight: 700; color: var(--gray-800); margin: 22px 0 10px; }
    .article-body p { margin-bottom: 16px; }
    .article-body ul, .article-body ol { padding-left: 24px; margin-bottom: 16px; }
    .article-body li { margin-bottom: 6px; }
    .article-body strong { color: var(--gray-800); }
    .article-body a { color: var(--green); }

    /* RELATED */
    .related-section { max-width: 860px; margin: 0 auto; padding: 0 5% 60px; }
    .related-section h3 { font-size: 1.1rem; font-weight: 700; color: var(--gray-800); margin-bottom: 20px; }
    .related-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 16px; }
    .related-card {
      background: #fff; border-radius: var(--radius); overflow: hidden;
      box-shadow: var(--shadow); text-decoration: none; transition: var(--transition);
      display: flex; flex-direction: column;
    }
    .related-card:hover { transform: translateY(-4px); box-shadow: 0 8px 30px rgba(0,0,0,0.12); }
    .related-img { width: 100%; aspect-ratio: 16/9; background: var(--green-light);
      display: flex; align-items: center; justify-content: center; font-size: 2.5rem; }
    .related-img img { width: 100%; height: 100%; object-fit: cover; }
    .related-content { padding: 14px 16px; flex-grow: 1; }
    .related-date { font-size: 0.72rem; color: var(--gray-600); margin-bottom: 5px; }
    .related-content h4 { font-size: 0.88rem; font-weight: 700; color: var(--gray-800); line-height: 1.4; }

    /* BACK BUTTON */
    .back-btn-wrap { max-width: 860px; margin: 0 auto; padding: 0 5% 20px; }
    .back-btn {
      display: inline-flex; align-items: center; gap: 8px;
      background: var(--green); color: #fff; padding: 11px 22px;
      border-radius: 8px; text-decoration: none; font-weight: 600;
      font-size: 0.88rem; transition: var(--transition);
    }
    .back-btn:hover { background: var(--green-dark); transform: translateY(-2px); color: #fff; }

    @media (max-width: 640px) {
      .article-body { padding: 24px 20px; }
      .article-hero { padding: 32px 4% 0; }
    }
  </style>
</head>
<body>

<nav>
  <a href="{{ route('home') }}" class="nav-brand">
    <div class="nav-logo">
      <img src="{{ asset('images/Logo-Yayasan.png') }}" alt="Logo" />
    </div>
    <div class="nav-title">
      Rumah Yatim Baiturrohim
      <span>Peduli Yatim & Dhuafa</span>
    </div>
  </a>
  <a href="{{ route('home') }}#berita" class="nav-back">← Kembali ke Beranda</a>
</nav>

<!-- Hero -->
<div class="article-hero">
  <div class="article-hero-inner">
    <span class="kategori-badge">{{ $berita->kategori }}</span>
    <h1>{{ $berita->judul }}</h1>
    <div class="article-meta">
      <span>📅 {{ $berita->tanggal_format }}</span>
      <span>✍️ {{ $berita->penulis?->name ?? 'Admin' }}</span>
    </div>
  </div>

  @if($berita->foto)
  <div class="article-foto">
    <img src="{{ $berita->foto_url }}" alt="{{ $berita->judul }}" />
  </div>
  @endif
</div>

<!-- Isi -->
<div class="article-wrap">
  <div class="article-body {{ $berita->foto ? '' : 'no-foto' }}">
    {!! $berita->isi !!}
  </div>
</div>

<!-- Berita Terkait -->
@if($terkait->isNotEmpty())
<div class="related-section">
  <h3>📰 Berita Terkait</h3>
  <div class="related-grid">
    @foreach($terkait as $item)
    <a href="{{ route('berita.show', $item->slug) }}" class="related-card">
      <div class="related-img">
        @if($item->foto)
          <img src="{{ $item->foto_url }}" alt="{{ $item->judul }}" />
        @else
          📰
        @endif
      </div>
      <div class="related-content">
        <div class="related-date">{{ $item->tanggal_format }}</div>
        <h4>{{ Str::limit($item->judul, 70) }}</h4>
      </div>
    </a>
    @endforeach
  </div>
</div>
@endif

<!-- Tombol kembali -->
<div class="back-btn-wrap">
  <a href="{{ route('home') }}#berita" class="back-btn">← Kembali ke Beranda</a>
</div>

</body>
</html>
