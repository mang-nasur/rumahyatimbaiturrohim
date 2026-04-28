<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Rumah Yatim Baiturrohim - Yayasan sosial yang melayani anak-anak yatim dan dhuafa dengan penuh kasih sayang. Donasi, beasiswa, dan pemberdayaan masyarakat." />
  <meta name="keywords" content="rumah yatim, yatim piatu, donasi, sedekah, zakat, baiturrohim, panti asuhan" />
  <meta name="author" content="Rumah Yatim Baiturrohim" />
  <meta property="og:title" content="Rumah Yatim Baiturrohim" />
  <meta property="og:description" content="Bersama kami meraih berkah melalui kepedulian kepada anak yatim dan dhuafa." />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="{{ url('/') }}" />
  <title>Rumah Yatim Baiturrohim | Peduli Yatim & Dhuafa</title>
  <link rel="icon" href="{{ asset('favicon.ico') }}" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Amiri:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet" />
  
  <style>
    /* === RESET & BASE === */
    *, *::before, *::after { 
      box-sizing: border-box; 
      margin: 0; 
      padding: 0; 
    }

    /* === CSS VARIABLES === */
    :root {
      --green: #1a7a4a;
      --green-dark: #145c38;
      --green-light: #e8f5ee;
      --gold: #d4a017;
      --gold-light: #fdf6e3;
      --white: #ffffff;
      --gray-100: #f8f9fa;
      --gray-200: #e9ecef;
      --gray-600: #6c757d;
      --gray-800: #343a40;
      --text: #2d2d2d;
      --shadow: 0 4px 20px rgba(0,0,0,0.08);
      --shadow-md: 0 8px 30px rgba(0,0,0,0.12);
      --shadow-hover: 0 12px 40px rgba(0,0,0,0.15);
      --radius: 12px;
      --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    html { 
      scroll-behavior: smooth; 
    }
    
    body { 
      font-family: 'Poppins', sans-serif; 
      color: var(--text); 
      background: var(--white); 
      line-height: 1.6; 
    }

    /* === NAVIGATION === */
    nav {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1000;
      background: rgba(255,255,255,0.97);
      backdrop-filter: blur(10px);
      box-shadow: 0 2px 15px rgba(0,0,0,0.08);
      padding: 0 5%;
      display: flex;
      align-items: center;
      justify-content: space-between;
      height: 70px;
    }
    
    .nav-brand { 
      display: flex; 
      align-items: center; 
      gap: 10px; 
      text-decoration: none; 
    }
    
    .nav-logo { 
      width: 42px; 
      height: 42px; 
      background: var(--green); 
      border-radius: 10px;
      display: flex; 
      align-items: center; 
      justify-content: center; 
      font-size: 22px; 
      overflow: hidden; 
      flex-shrink: 0;
    }
    
    .nav-logo img { 
      width: 28px; 
      height: 28px; 
      object-fit: contain; 
    }
    
    .nav-title { 
      font-weight: 700; 
      color: var(--green); 
      font-size: 0.95rem; 
      line-height: 1.2; 
    }
    
    .nav-title span { 
      display: block; 
      font-weight: 400; 
      font-size: 0.7rem; 
      color: var(--gray-600); 
    }
    
    .nav-links { 
      display: flex; 
      align-items: center; 
      gap: 28px; 
      list-style: none; 
    }
    
    .nav-links a { 
      text-decoration: none; 
      color: var(--gray-800); 
      font-size: 0.88rem; 
      font-weight: 500;
      transition: var(--transition); 
      padding: 5px 0; 
      border-bottom: 2px solid transparent; 
    }
    
    .nav-links a:hover { 
      color: var(--green); 
      border-bottom-color: var(--green); 
    }
    
    .nav-cta { 
      background: var(--green) !important; 
      color: var(--white) !important; 
      padding: 10px 22px !important;
      border-radius: 8px !important; 
      border-bottom: none !important; 
    }
    
    .nav-cta:hover { 
      background: var(--green-dark) !important; 
      transform: translateY(-2px); 
      box-shadow: 0 4px 12px rgba(26, 122, 74, 0.3);
    }
    
    .hamburger { 
      display: none; 
      flex-direction: column; 
      gap: 5px; 
      cursor: pointer; 
      padding: 5px; 
    }
    
    .hamburger span { 
      width: 24px; 
      height: 2px; 
      background: var(--green); 
      border-radius: 2px; 
      transition: var(--transition); 
    }

    /* === HERO SECTION === */
    .hero {
      min-height: 100vh;
      display: flex;
      align-items: center;
      background: linear-gradient(135deg, #0d4a28 0%, #1a7a4a 50%, #2ecc71 100%);
      position: relative;
      overflow: hidden;
      padding-top: 70px;
    }
    
    .hero::before {
      content: '';
      position: absolute;
      inset: 0;
      background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    
    .hero-content { 
      position: relative; 
      z-index: 2; 
      max-width: 1280px; 
      margin: 0 auto; 
      padding: 60px 5%; 
      display: grid; 
      grid-template-columns: minmax(0, 1.05fr) minmax(540px, 0.95fr); 
      gap: 60px; 
      align-items: center; 
    }
    
    .hero-badge { 
      display: inline-flex; 
      align-items: center; 
      gap: 8px; 
      background: rgba(255,255,255,0.15); 
      color: #fff; 
      padding: 6px 16px; 
      border-radius: 50px; 
      font-size: 0.8rem; 
      font-weight: 500; 
      margin-bottom: 20px; 
      backdrop-filter: blur(10px); 
    }
    
    .hero h1 { 
      font-size: clamp(2rem, 4vw, 3.2rem); 
      font-weight: 800; 
      color: #fff; 
      line-height: 1.2; 
      margin-bottom: 20px; 
    }
    
    .hero h1 em { 
      font-style: normal; 
      color: var(--gold); 
    }
    
    .hero p { 
      color: rgba(255,255,255,0.85); 
      font-size: 1.05rem; 
      margin-bottom: 35px; 
      line-height: 1.8; 
    }
    
    .hero-actions { 
      display: flex; 
      gap: 16px; 
      flex-wrap: wrap; 
    }
    
    .btn-primary { 
      background: var(--gold); 
      color: #fff; 
      padding: 14px 32px; 
      border-radius: 10px; 
      font-weight: 700; 
      text-decoration: none; 
      font-size: 0.95rem; 
      transition: var(--transition); 
      display: inline-flex; 
      align-items: center; 
      gap: 8px; 
    }
    
    .btn-primary:hover { 
      background: #b8860b; 
      transform: translateY(-2px); 
      box-shadow: 0 8px 25px rgba(212,160,23,0.4); 
    }
    
    .btn-outline { 
      background: transparent; 
      color: #fff; 
      padding: 14px 32px; 
      border-radius: 10px; 
      font-weight: 600; 
      text-decoration: none; 
      font-size: 0.95rem; 
      border: 2px solid rgba(255,255,255,0.5); 
      transition: var(--transition); 
    }
    
    .btn-outline:hover { 
      background: rgba(255,255,255,0.15); 
      border-color: #fff; 
    }
    
    .hero-card { 
      background: rgba(255,255,255,0.12); 
      backdrop-filter: blur(20px); 
      border: 1px solid rgba(255,255,255,0.2); 
      border-radius: 20px; 
      padding: 38px 44px; 
      width: 100%; 
    }
    
    .hero-card h3 { 
      color: #fff; 
      font-size: 1.1rem; 
      margin-bottom: 20px; 
    }
    
    /* === STAT GRID - IMPROVED SYMMETRY === */
    .stat-grid { 
      display: grid; 
      grid-template-columns: repeat(2, 1fr); 
      gap: 16px; 
    }
    
    .stat-item { 
      text-align: center; 
      background: rgba(255,255,255,0.12); 
      border-radius: 12px; 
      padding: 24px 16px; 
      min-height: 140px;
      display: flex; 
      flex-direction: column; 
      align-items: center;
      justify-content: center; 
      transition: var(--transition);
    }
    
    .stat-item:hover {
      background: rgba(255,255,255,0.18);
      transform: translateY(-4px);
    }
    
    .stat-num { 
      font-size: 2rem; 
      font-weight: 800; 
      color: var(--gold); 
      display: block; 
      line-height: 1.2;
      margin-bottom: 8px;
    }
    
    .stat-label { 
      font-size: 0.8rem; 
      color: #ffffff; 
      font-weight: 500; 
      line-height: 1.4;
    }
    
    .ayat { 
      font-family: 'Amiri', serif; 
      font-size: 1.5rem; 
      color: rgba(255,255,255,0.9); 
      text-align: right; 
      direction: rtl; 
      margin-top: 20px; 
      padding-top: 20px; 
      border-top: 1px solid rgba(255,255,255,0.2); 
      line-height: 2; 
    }
    
    .ayat-trans { 
      font-size: 0.78rem; 
      color: rgba(255,255,255,0.7); 
      direction: ltr; 
      text-align: left; 
      margin-top: 8px; 
      font-style: italic; 
    }

    /* === SECTION LAYOUT === */
    section { 
      padding: 80px 5%; 
    }
    
    .section-header { 
      text-align: center; 
      margin-bottom: 55px; 
    }
    
    .section-tag { 
      display: inline-block; 
      background: var(--green-light); 
      color: var(--green); 
      padding: 5px 16px; 
      border-radius: 50px; 
      font-size: 0.8rem; 
      font-weight: 600; 
      margin-bottom: 12px; 
    }
    
    .section-header h2 { 
      font-size: clamp(1.6rem, 3vw, 2.4rem); 
      font-weight: 800; 
      color: var(--gray-800); 
      margin-bottom: 12px; 
    }
    
    .section-header p { 
      color: var(--gray-600); 
      max-width: 560px; 
      margin: 0 auto; 
      font-size: 0.97rem; 
    }
    
    .divider { 
      width: 60px; 
      height: 4px; 
      background: linear-gradient(90deg, var(--green), var(--gold)); 
      border-radius: 2px; 
      margin: 15px auto 0; 
    }

    /* === PROGRAM SECTION - IMPROVED GRID === */
    .program-section { 
      background: var(--gray-100); 
    }
    
    .program-grid { 
      display: grid; 
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
      gap: 24px; 
      max-width: 1200px; 
      margin: 0 auto; 
    }
    
    .program-card { 
      background: #fff; 
      border-radius: var(--radius); 
      padding: 32px 28px; 
      box-shadow: var(--shadow); 
      transition: var(--transition); 
      border-top: 4px solid transparent;
      display: flex;
      flex-direction: column;
      height: 100%;
    }
    
    .program-card:hover { 
      transform: translateY(-6px); 
      box-shadow: var(--shadow-hover); 
      border-top-color: var(--green); 
    }
    
    /* === PROGRAM ICON - CENTERED & ALIGNED === */
    .program-icon { 
      width: 64px; 
      height: 64px; 
      background: var(--green-light); 
      border-radius: 14px; 
      display: flex; 
      align-items: center; 
      justify-content: center; 
      font-size: 32px; 
      margin-bottom: 20px;
      flex-shrink: 0;
      transition: var(--transition);
    }
    
    .program-card:hover .program-icon {
      background: var(--green);
      transform: scale(1.1);
    }
    
    .program-card h3 { 
      font-size: 1.1rem; 
      font-weight: 700; 
      color: var(--gray-800); 
      margin-bottom: 10px; 
    }
    
    .program-card p { 
      color: var(--gray-600); 
      font-size: 0.88rem; 
      line-height: 1.7;
      flex-grow: 1;
    }

    /* === GALLERY SECTION - IMPROVED GRID === */
    .gallery-grid { 
      display: grid; 
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
      gap: 16px; 
      max-width: 1100px; 
      margin: 0 auto; 
    }
    
    .gallery-item { 
      border-radius: 12px; 
      overflow: hidden; 
      aspect-ratio: 4/3; 
      background: var(--green-light); 
      display: flex; 
      align-items: center; 
      justify-content: center; 
      font-size: 3rem; 
      transition: var(--transition); 
      position: relative; 
      padding: 22px; 
      text-align: left; 
    }
    
    .gallery-item:hover { 
      transform: scale(1.02); 
      box-shadow: var(--shadow-md); 
    }
    
    .gallery-item.tall { 
      grid-row: span 2; 
    }
    
    .gallery-item.wide { 
      grid-column: span 2; 
    }
    
    .gallery-copy { 
      width: 100%; 
      align-self: end; 
      color: var(--white); 
    }
    
    .gallery-themed { 
      background: linear-gradient(135deg, #145c38 0%, #1a7a4a 100%); 
    }
    
    .gallery-copy strong { 
      display: block; 
      font-size: 1rem; 
      margin-bottom: 8px; 
    }
    
    .gallery-copy span { 
      display: block; 
      font-size: 0.85rem; 
      line-height: 1.6; 
      color: rgba(255,255,255,0.82); 
    }

    /* === DONATION SECTION === */
    .donasi-section { 
      background: linear-gradient(135deg, var(--green-dark), var(--green)); 
    }
    
    .donasi-inner { 
      max-width: 900px; 
      margin: 0 auto; 
      text-align: center; 
    }
    
    .donasi-inner .section-tag { 
      background: rgba(255,255,255,0.2); 
      color: #fff; 
    }
    
    .donasi-inner h2 { 
      color: #fff; 
    }
    
    .donasi-inner p { 
      color: rgba(255,255,255,0.85); 
    }
    
    .donasi-inner .divider { 
      background: linear-gradient(90deg, rgba(255,255,255,0.3), var(--gold)); 
    }
    
    .rekening-cards { 
      display: flex; 
      gap: 20px; 
      justify-content: center; 
      flex-wrap: wrap; 
      margin: 35px 0; 
    }
    
    .rekening-card { 
      background: rgba(255,255,255,0.12); 
      backdrop-filter: blur(10px); 
      border: 1px solid rgba(255,255,255,0.2); 
      border-radius: 14px; 
      padding: 24px 30px; 
      min-width: 220px; 
      text-align: left; 
      transition: var(--transition); 
    }
    
    .rekening-card:hover { 
      background: rgba(255,255,255,0.2); 
      transform: translateY(-4px);
    }
    
    .rekening-bank { 
      font-size: 0.82rem; 
      color: rgba(255,255,255,0.7); 
      margin-bottom: 6px; 
    }
    
    .rekening-no { 
      font-size: 1.25rem; 
      font-weight: 700; 
      color: #fff; 
      letter-spacing: 0.5px; 
      margin-bottom: 6px;
      font-family: 'Courier New', monospace;
    }
    
    .rekening-an { 
      font-size: 0.82rem; 
      color: rgba(255,255,255,0.7); 
    }

    /* === NEWS SECTION === */
    .berita-grid { 
      display: grid; 
      grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); 
      gap: 24px; 
      max-width: 1200px; 
      margin: 0 auto; 
    }
    
    .berita-card { 
      background: #fff; 
      border-radius: var(--radius); 
      overflow: hidden; 
      box-shadow: var(--shadow); 
      transition: var(--transition); 
      display: flex;
      flex-direction: column;
      height: 100%;
    }
    
    .berita-card:hover { 
      transform: translateY(-6px); 
      box-shadow: var(--shadow-hover); 
    }
    
    .berita-img { 
      width: 100%; 
      aspect-ratio: 16/9; 
      background: var(--green-light); 
      display: flex; 
      align-items: center; 
      justify-content: center; 
      font-size: 4rem;
      flex-shrink: 0;
    }
    
    .berita-content { 
      padding: 22px 24px;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
    }
    
    .berita-date { 
      font-size: 0.78rem; 
      color: var(--gray-600); 
      margin-bottom: 8px; 
    }
    
    .berita-card h3 { 
      font-size: 1.05rem; 
      font-weight: 700; 
      color: var(--gray-800); 
      margin-bottom: 10px; 
      line-height: 1.4; 
    }
    
    .berita-card p { 
      color: var(--gray-600); 
      font-size: 0.88rem; 
      line-height: 1.6; 
      margin-bottom: 14px;
      flex-grow: 1;
    }
    
    .berita-link { 
      color: var(--green); 
      font-weight: 600; 
      font-size: 0.88rem; 
      text-decoration: none; 
      transition: var(--transition);
      align-self: flex-start;
    }
    
    .berita-link:hover { 
      color: var(--green-dark); 
      gap: 8px; 
    }

    /* === CONTACT SECTION === */
    .kontak-grid { 
      display: grid; 
      grid-template-columns: 1fr 1fr; 
      gap: 40px; 
      max-width: 1100px; 
      margin: 0 auto; 
    }
    
    .kontak-info h3 { 
      font-size: 1.5rem; 
      font-weight: 700; 
      color: var(--gray-800); 
      margin-bottom: 16px; 
    }
    
    .kontak-info p { 
      color: var(--gray-600); 
      margin-bottom: 28px; 
      line-height: 1.8; 
    }
    
    .contact-item { 
      display: flex; 
      align-items: flex-start; 
      gap: 14px; 
      margin-bottom: 18px; 
    }
    
    .contact-icon { 
      width: 44px; 
      height: 44px; 
      background: var(--green-light); 
      border-radius: 10px; 
      display: flex; 
      align-items: center; 
      justify-content: center; 
      font-size: 20px;
      flex-shrink: 0;
    }
    
    .contact-text strong { 
      display: block; 
      font-weight: 600; 
      color: var(--gray-800); 
      margin-bottom: 3px; 
    }
    
    .contact-text span { 
      color: var(--gray-600); 
      font-size: 0.9rem; 
    }

    /* === FORM === */
    .form-group { 
      margin-bottom: 20px; 
    }
    
    .form-group label { 
      display: block; 
      margin-bottom: 8px; 
      font-weight: 600; 
      color: var(--gray-800); 
      font-size: 0.9rem; 
    }
    
    .form-group input,
    .form-group select,
    .form-group textarea { 
      width: 100%; 
      padding: 12px 16px; 
      border: 2px solid var(--gray-200); 
      border-radius: 8px; 
      font-family: inherit; 
      font-size: 0.9rem; 
      transition: var(--transition); 
    }
    
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus { 
      outline: none; 
      border-color: var(--green); 
      box-shadow: 0 0 0 3px rgba(26, 122, 74, 0.1); 
    }
    
    .form-group textarea { 
      min-height: 120px; 
      resize: vertical; 
    }
    
    .btn-submit { 
      width: 100%; 
      background: var(--green); 
      color: #fff; 
      padding: 14px 24px; 
      border: none; 
      border-radius: 8px; 
      font-weight: 700; 
      font-size: 0.95rem; 
      cursor: pointer; 
      transition: var(--transition); 
    }
    
    .btn-submit:hover { 
      background: var(--green-dark); 
      transform: translateY(-2px); 
      box-shadow: 0 6px 20px rgba(26, 122, 74, 0.3); 
    }

    /* === FOOTER === */
    footer { 
      background: var(--gray-800); 
      color: rgba(255,255,255,0.8); 
      padding: 60px 5% 30px; 
    }
    
    .footer-grid { 
      display: grid; 
      grid-template-columns: 2fr 1fr 1fr 1fr; 
      gap: 40px; 
      max-width: 1200px; 
      margin: 0 auto 40px; 
    }
    
    .footer-about p { 
      margin: 12px 0 20px; 
      line-height: 1.8; 
      font-size: 0.9rem; 
    }
    
    .footer-socials { 
      display: flex; 
      gap: 10px; 
    }
    
    .social-btn { 
      width: 40px; 
      height: 40px; 
      background: rgba(255,255,255,0.1); 
      border-radius: 8px; 
      display: flex; 
      align-items: center; 
      justify-content: center; 
      font-size: 18px; 
      text-decoration: none; 
      transition: var(--transition); 
    }
    
    .social-btn:hover { 
      background: var(--green); 
      transform: translateY(-3px); 
    }
    
    .footer-col h4 { 
      color: #fff; 
      font-size: 1rem; 
      margin-bottom: 16px; 
    }
    
    .footer-col ul { 
      list-style: none; 
    }
    
    .footer-col ul li { 
      margin-bottom: 10px; 
    }
    
    .footer-col ul li a { 
      color: rgba(255,255,255,0.7); 
      text-decoration: none; 
      font-size: 0.88rem; 
      transition: var(--transition); 
    }
    
    .footer-col ul li a:hover { 
      color: #fff; 
      padding-left: 5px; 
    }
    
    .footer-bottom { 
      text-align: center; 
      padding-top: 30px; 
      border-top: 1px solid rgba(255,255,255,0.1); 
    }
    
    .footer-bottom p { 
      font-size: 0.85rem; 
      color: rgba(255,255,255,0.6); 
    }

    /* === FLOATING BUTTONS === */
    .float-wa { 
      position: fixed; 
      bottom: 30px; 
      right: 30px; 
      width: 56px; 
      height: 56px; 
      background: #25D366; 
      border-radius: 50%; 
      display: flex; 
      align-items: center; 
      justify-content: center; 
      font-size: 28px; 
      text-decoration: none; 
      box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4); 
      transition: var(--transition); 
      z-index: 999; 
    }
    
    .float-wa:hover { 
      transform: scale(1.1) translateY(-3px); 
      box-shadow: 0 8px 30px rgba(37, 211, 102, 0.5); 
    }
    
    #back-top { 
      position: fixed; 
      bottom: 100px; 
      right: 30px; 
      width: 48px; 
      height: 48px; 
      background: var(--green); 
      color: #fff; 
      border: none; 
      border-radius: 50%; 
      font-size: 20px; 
      cursor: pointer; 
      opacity: 0; 
      visibility: hidden; 
      transition: var(--transition); 
      z-index: 999; 
    }
    
    #back-top.show { 
      opacity: 1; 
      visibility: visible; 
    }
    
    #back-top:hover { 
      background: var(--green-dark); 
      transform: translateY(-3px); 
    }

    /* === RESPONSIVE === */
    @media (max-width: 1024px) {
      .hero-content { 
        grid-template-columns: 1fr; 
        gap: 40px; 
      }
      
      .program-grid { 
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); 
      }
      
      .footer-grid { 
        grid-template-columns: 1fr 1fr; 
      }
      
      .kontak-grid { 
        grid-template-columns: 1fr; 
      }
    }

    @media (max-width: 768px) {
      nav { 
        padding: 0 4%; 
      }
      
      .nav-links { 
        position: fixed; 
        top: 70px; 
        left: 0; 
        right: 0; 
        background: rgba(255,255,255,0.98); 
        flex-direction: column; 
        align-items: flex-start; 
        padding: 20px 5%; 
        gap: 16px; 
        transform: translateY(-120%); 
        transition: var(--transition); 
        box-shadow: 0 4px 20px rgba(0,0,0,0.1); 
      }
      
      .nav-links.open { 
        transform: translateY(0); 
      }
      
      .hamburger { 
        display: flex; 
      }
      
      .stat-grid { 
        grid-template-columns: 1fr; 
      }
      
      .program-grid { 
        grid-template-columns: 1fr; 
      }
      
      .gallery-grid { 
        grid-template-columns: 1fr; 
      }
      
      .gallery-item.tall,
      .gallery-item.wide { 
        grid-row: auto; 
        grid-column: auto; 
      }
      
      .berita-grid { 
        grid-template-columns: 1fr; 
      }
      
      .footer-grid { 
        grid-template-columns: 1fr; 
      }
      
      .float-wa { 
        bottom: 20px; 
        right: 20px; 
        width: 52px; 
        height: 52px; 
      }
      
      #back-top { 
        bottom: 85px; 
        right: 20px; 
      }
    }
  </style>
</head>

<body>

<!-- === NAVIGATION === -->
<nav>
  <a href="#beranda" class="nav-brand">
    <div class="nav-logo">
      <img src="{{ asset('images/Logo-Yayasan.png') }}" alt="Logo" />
    </div>
    <div class="nav-title">
      Rumah Yatim Baiturrohim
      <span>Peduli Yatim & Dhuafa</span>
    </div>
  </a>
  <ul class="nav-links" id="navLinks">
    <li><a href="#beranda">Beranda</a></li>
    <li><a href="#program">Program</a></li>
    <li><a href="#galeri">Galeri</a></li>
    <li><a href="#berita">Berita</a></li>
    <li><a href="#donasi" class="nav-cta">💝 Donasi</a></li>
  </ul>
  <div class="hamburger" id="hamburger">
    <span></span>
    <span></span>
    <span></span>
  </div>
</nav>

<!-- === HERO SECTION === -->
<section class="hero" id="beranda">
  <div class="hero-content">
    <div>
      <div class="hero-badge">
        ✨ Berbagi Kasih, Meraih Berkah
      </div>
      <h1>Bersama Kita Muliakan <em>Anak Yatim</em> dan Dhuafa</h1>
      <p>Rumah Yatim Baiturrohim adalah yayasan sosial yang telah melayani ribuan anak yatim sejak 1992. Mari bersama kami mewujudkan masa depan cerah bagi mereka.</p>
      <div class="hero-actions">
        <a href="#donasi" class="btn-primary">💝 Donasi Sekarang</a>
        <a href="#program" class="btn-outline">Lihat Program</a>
      </div>
    </div>
    <div class="hero-card">
      <h3>📊 Dampak Kami</h3>
      <div class="stat-grid">
        <div class="stat-item">
          <span class="stat-num">80+</span>
          <span class="stat-label">Anak Yatim Terlayani</span>
        </div>
        <div class="stat-item">
          <span class="stat-num">34</span>
          <span class="stat-label">Tahun Berdedikasi</span>
        </div>
        <div class="stat-item">
          <span class="stat-num">5+</span>
          <span class="stat-label">Program Aktif</span>
        </div>
        <div class="stat-item">
          <span class="stat-num">1.000+</span>
          <span class="stat-label">Donatur Setia</span>
        </div>
      </div>
      <div class="ayat">
        وَيُطْعِمُونَ الطَّعَامَ عَلَىٰ حُبِّهِ مِسْكِينًا وَيَتِيمًا وَأَسِيرًا
      </div>
      <div class="ayat-trans">
        "Dan mereka memberikan makanan yang disukainya kepada orang miskin, anak yatim dan orang yang ditawan." (QS. Al-Insan: 8)
      </div>
    </div>
  </div>
</section>

<!-- === PROGRAM SECTION === -->
<section class="program-section" id="program">
  <div class="section-header">
    <span class="section-tag">Program Kami</span>
    <h2>Berbagai Layanan untuk Anak Yatim</h2>
    <p>Kami menyediakan berbagai program untuk memastikan anak-anak yatim mendapat hak pendidikan, kesehatan, dan pengembangan diri yang layak.</p>
    <div class="divider"></div>
  </div>
  <div class="program-grid">
    <div class="program-card">
      <div class="program-icon">📚</div>
      <h3>Pendidikan Formal</h3>
      <p>Beasiswa penuh dari SD hingga Perguruan Tinggi dengan kualitas pendidikan terbaik dan bimbingan belajar intensif.</p>
    </div>
    <div class="program-card">
      <div class="program-icon">🕌</div>
      <h3>Pembinaan Spiritual</h3>
      <p>Program tahfidz, kajian agama, dan pembinaan akhlak untuk membentuk karakter Islami yang kuat.</p>
    </div>
    <div class="program-card">
      <div class="program-icon">🏥</div>
      <h3>Kesehatan</h3>
      <p>Pemeriksaan rutin, jaminan kesehatan, gizi seimbang, dan akses pengobatan gratis untuk semua anak asuh.</p>
    </div>
    <div class="program-card">
      <div class="program-icon">💻</div>
      <h3>Keterampilan IT</h3>
      <p>Pelatihan komputer, programming, desain grafis untuk mempersiapkan mereka di era digital.</p>
    </div>
    <div class="program-card">
      <div class="program-icon">🎨</div>
      <h3>Pengembangan Bakat</h3>
      <p>Ekstrakurikuler olahraga, seni, musik, dan kegiatan kreativitas untuk menggali potensi mereka.</p>
    </div>
    <div class="program-card">
      <div class="program-icon">🤝</div>
      <h3>Pemberdayaan Ekonomi</h3>
      <p>Pelatihan kewirausahaan dan keterampilan kerja untuk kemandirian finansial anak asuh dewasa.</p>
    </div>
  </div>
</section>

<!-- === GALLERY SECTION === -->
<section id="galeri">
  <div class="section-header">
    <span class="section-tag">Galeri</span>
    <h2>Momen Kebahagiaan Bersama</h2>
    <p>Lihat kegembiraan dan harapan yang terpancar dari setiap kegiatan kami bersama anak-anak yatim.</p>
    <div class="divider"></div>
  </div>
  <div class="gallery-grid">
    <div class="gallery-item">📖</div>
    <div class="gallery-item">🎓</div>
    <div class="gallery-item">🍲</div>
    <div class="gallery-item tall gallery-themed">
      <div class="gallery-copy">
        <strong>Kegiatan Harian Kami</strong>
        <span>Mendampingi tumbuh kembang mereka dengan penuh kasih sayang dan perhatian setiap hari.</span>
      </div>
    </div>
    <div class="gallery-item">⚽</div>
    <div class="gallery-item">🎨</div>
  </div>
</section>

<!-- === DONATION SECTION === -->
<section class="donasi-section" id="donasi">
  <div class="donasi-inner">
    <span class="section-tag">Donasi</span>
    <h2>Salurkan Kepedulian Anda</h2>
    <p>Setiap rupiah yang Anda berikan adalah harapan baru bagi mereka. Mari bersama membangun masa depan yang lebih cerah.</p>
    <div class="divider"></div>
    <div class="rekening-cards">
      <div class="rekening-card">
        <div class="rekening-bank">Bank Mandiri</div>
        <div class="rekening-no">1420045678901</div>
        <div class="rekening-an">a.n. Rumah Yatim Baiturrohim YYS</div>
      </div>
      <div class="rekening-card">
        <div class="rekening-bank">Bank BSI</div>
        <div class="rekening-no">7294768763</div>
        <div class="rekening-an">a.n. Rumah Yatim Baiturrohim YYS</div>
      </div>
      <div class="rekening-card">
        <div class="rekening-bank">Bank BRI</div>
        <div class="rekening-no">007601098765432</div>
        <div class="rekening-an">a.n. Rumah Yatim Baiturrohim YYS</div>
      </div>
    </div>
    <a href="#kontak" class="btn-primary">📞 Konfirmasi Donasi</a>
  </div>
</section>

<!-- === NEWS SECTION === -->
<section id="berita">
  <div class="section-header">
    <span class="section-tag">Berita & Kegiatan</span>
    <h2>Update Terbaru Kami</h2>
    <p>Ikuti perkembangan dan kegiatan terbaru dari Rumah Yatim Baiturrohim.</p>
    <div class="divider"></div>
  </div>
  <div class="berita-grid">
    <div class="berita-card">
      <div class="berita-img">📰</div>
      <div class="berita-content">
        <div class="berita-date">15 Januari 2025</div>
        <h3>Perayaan Milad Yayasan ke-32</h3>
        <p>Alhamdulillah, kami merayakan 32 tahun pengabdian dalam melayani anak yatim dan dhuafa dengan penuh syukur.</p>
        <a href="#" class="berita-link">Baca Selengkapnya →</a>
      </div>
    </div>
    <div class="berita-card">
      <div class="berita-img">🎓</div>
      <div class="berita-content">
        <div class="berita-date">10 Januari 2025</div>
        <h3>25 Anak Asuh Lulus Perguruan Tinggi</h3>
        <p>Prestasi membanggakan! 25 anak asuh kami berhasil menyelesaikan studi S1 dengan nilai memuaskan.</p>
        <a href="#" class="berita-link">Baca Selengkapnya →</a>
      </div>
    </div>
    <div class="berita-card">
      <div class="berita-img">🏆</div>
      <div class="berita-content">
        <div class="berita-date">5 Januari 2025</div>
        <h3>Juara Lomba Tahfidz Tingkat Nasional</h3>
        <p>Bangga! Santri kami meraih juara 1 lomba tahfidz Quran 30 juz tingkat nasional.</p>
        <a href="#" class="berita-link">Baca Selengkapnya →</a>
      </div>
    </div>
  </div>
</section>

<!-- === CONTACT SECTION === -->
<section id="kontak">
  <div class="section-header">
    <span class="section-tag">Hubungi Kami</span>
    <h2>Ada Pertanyaan? Silakan Hubungi</h2>
    <p>Kami siap menjawab pertanyaan Anda seputar program, donasi, atau pendaftaran anak asuh.</p>
    <div class="divider"></div>
  </div>
  <div class="kontak-grid">
    <div class="kontak-info">
      <h3>Informasi Kontak</h3>
      <p>Kami terbuka untuk kolaborasi, pertanyaan, maupun kunjungan langsung. Jangan ragu untuk menghubungi kami.</p>
      
      <div class="contact-item">
        <div class="contact-icon">📍</div>
        <div class="contact-text">
          <strong>Alamat</strong>
          <span>Jl. Raya Ciledug No. 123, Tangerang, Banten 15151</span>
        </div>
      </div>
      
      <div class="contact-item">
        <div class="contact-icon">📧</div>
        <div class="contact-text">
          <strong>Email</strong>
          <span>info@baiturrohim.or.id</span>
        </div>
      </div>
      
      <div class="contact-item">
        <div class="contact-icon">📞</div>
        <div class="contact-text">
          <strong>Telepon</strong>
          <span>+62 877-7401-7804</span>
        </div>
      </div>
      
      <div class="contact-item">
        <div class="contact-icon">⏰</div>
        <div class="contact-text">
          <strong>Jam Operasional</strong>
          <span>Senin - Jumat: 08.00 - 16.00 WIB<br>Sabtu: 08.00 - 12.00 WIB</span>
        </div>
      </div>
    </div>
    
    <div>
      <form id="contactForm">
        <div class="form-group">
          <label for="nama">Nama Lengkap *</label>
          <input type="text" id="nama" name="nama" placeholder="Masukkan nama Anda" required />
        </div>
        <div class="form-group">
          <label for="email">Kontak / WhatsApp *</label>
          <input type="text" id="email" name="email" placeholder="Masukkan nomor atau kontak Anda" required />
        </div>
        <div class="form-group">
          <label for="subjek">Subjek</label>
          <select id="subjek" name="subjek">
            <option value="">Pilih subjek</option>
            <option value="donasi">Informasi Donasi</option>
            <option value="program">Informasi Program</option>
            <option value="akun">Pendaftaran Akun Orang Tua</option>
            <option value="lainnya">Lainnya</option>
          </select>
        </div>
        <div class="form-group">
          <label for="pesan">Pesan *</label>
          <textarea id="pesan" name="pesan" placeholder="Tuliskan pesan Anda..." required></textarea>
        </div>
        <button type="submit" class="btn-submit">📨 Kirim Pesan</button>
      </form>
      <div style="margin-top:14px; display:grid; gap:10px;">
        <a href="{{ route('register') }}" class="btn-submit" style="text-decoration:none; display:flex; justify-content:center; align-items:center; background:var(--gold);">
          Daftar Akun Orang Tua
        </a>
        <a href="{{ route('login') }}" class="btn-submit" style="text-decoration:none; display:flex; justify-content:center; align-items:center; background:#fff; color:var(--green); border:1.5px solid var(--green);">
          Login
        </a>
      </div>
    </div>
  </div>
</section>

<!-- === FOOTER === -->
<footer>
  <div class="footer-grid">
    <div class="footer-about">
      <div style="display:flex; align-items:center; gap:10px; margin-bottom:5px">
        <div class="nav-logo">
          <img src="{{ asset('images/Logo-Yayasan.png') }}" alt="Logo Rumah Yatim Baiturrohim" />
        </div>
        <div style="font-weight:700; color:#fff; font-size:1rem">Rumah Yatim Baiturrohim</div>
      </div>
      <p>Yayasan sosial yang berdedikasi untuk memuliakan anak-anak yatim dan kaum dhuafa melalui program pendidikan, kesehatan, dan pemberdayaan masyarakat sejak 1992.</p>
      <div class="footer-socials">
        <a class="social-btn" href="https://www.instagram.com/baiturrohim_ciledug/" target="_blank" rel="noopener" title="Instagram">📸</a>
        <a class="social-btn" href="https://www.youtube.com/@baiturrohim_ciledug" target="_blank" rel="noopener" title="YouTube">▶️</a>
        <a class="social-btn" href="https://wa.me/6287774017804?text=Assalamu%27alaikum" target="_blank" rel="noopener" title="WhatsApp">💬</a>
      </div>
    </div>
    <div class="footer-col">
      <h4>Navigasi</h4>
      <ul>
        <li><a href="#beranda">Beranda</a></li>
        <li><a href="#program">Program</a></li>
        <li><a href="#galeri">Galeri</a></li>
        <li><a href="#berita">Berita</a></li>
        <li><a href="#kontak">Kontak</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Program</h4>
      <ul>
        <li><a href="#program">Pendidikan Formal</a></li>
        <li><a href="#program">Pembinaan Spiritual</a></li>
        <li><a href="#program">Kesehatan</a></li>
        <li><a href="#program">Keterampilan IT</a></li>
        <li><a href="#program">Pengembangan Bakat</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Donasi</h4>
      <ul>
        <li><a href="#donasi">Cara Donasi</a></li>
        <li><a href="#donasi">Rekening Bank</a></li>
        <li><a href="#donasi">Konfirmasi Donasi</a></li>
        <li><a href="{{ route('register') }}">Daftar Orang Tua</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <p>© {{ date('Y') }} Rumah Yatim Baiturrohim. Terdaftar sebagai yayasan sosial dan dikelola untuk anak-anak yatim Indonesia.</p>
  </div>
</footer>

<!-- === FLOATING BUTTONS === -->
<a class="float-wa" href="https://wa.me/6287774017804" target="_blank" rel="noopener" title="Hubungi via WhatsApp">💬</a>
<button id="back-top" title="Kembali ke atas">↑</button>

<!-- === JAVASCRIPT === -->
<script>
  // Mobile Menu Toggle
  const hamburger = document.getElementById('hamburger');
  const navLinks = document.getElementById('navLinks');
  hamburger.addEventListener('click', () => {
    navLinks.classList.toggle('open');
  });

  // Back to Top Button
  const backTop = document.getElementById('back-top');
  window.addEventListener('scroll', () => {
    backTop.classList.toggle('show', window.scrollY > 400);
  });
  backTop.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });

  // Active Navigation Link
  const sections = document.querySelectorAll('section[id]');
  const links = document.querySelectorAll('.nav-links a');
  window.addEventListener('scroll', () => {
    let current = '';
    sections.forEach(s => {
      if (window.scrollY >= s.offsetTop - 100) current = s.id;
    });
    links.forEach(a => {
      a.style.color = a.getAttribute('href') === '#' + current ? 'var(--green)' : '';
    });
  });

  // Contact Form Submit
  document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Terima kasih! Pesan Anda telah dikirim. Kami akan segera menghubungi Anda.');
    this.reset();
  });

  // Scroll Animation Observer
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = '1';
        entry.target.style.transform = 'translateY(0)';
      }
    });
  }, { threshold: 0.1 });

  document.querySelectorAll('.program-card, .berita-card, .gallery-item').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(20px)';
    el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(el);
  });
</script>

</body>
</html>