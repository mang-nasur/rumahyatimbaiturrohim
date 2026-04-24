
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rumah Yatim Baiturrohim - Beranda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #1a5276;
            --accent:  #d4ac0d;
        }
        body { font-family: 'Segoe UI', sans-serif; }

        /* Navbar */
        .navbar-brand { font-weight: 700; letter-spacing: .5px; }
        .navbar { background: var(--primary) !important; }

        /* Hero */
        .hero {
            position: relative;
            color: #fff;
            padding: 100px 0 80px;
        }
        .hero h1 { font-size: 2.8rem; font-weight: 700; text-shadow: 0 2px 10px rgba(0,0,0,.6); }
        .hero p.lead { color: #fff !important; text-shadow: 0 1px 6px rgba(0,0,0,.5); }
        .hero .text-warning { color: #f4d03f !important; text-shadow: 0 1px 4px rgba(0,0,0,.4); }
        .hero .badge-stat {
            background: rgba(255,255,255,.15);
            border-radius: 12px;
            padding: 16px 24px;
            text-align: center;
            backdrop-filter: blur(4px);
        }
        .hero .badge-stat h2 { font-size: 2.5rem; font-weight: 700; margin: 0; text-shadow: 0 2px 6px rgba(0,0,0,.4); }
        .hero .badge-stat p { text-shadow: 0 1px 3px rgba(0,0,0,.3); }

        /* Section */
        section { padding: 72px 0; }
        section:nth-child(even) { background: #f8f9fa; }
        .section-title { font-size: 1.8rem; font-weight: 700; color: var(--primary); }
        .section-title::after {
            content: '';
            display: block;
            width: 50px;
            height: 4px;
            background: var(--accent);
            margin-top: 8px;
        }

        /* Program cards */
        .program-card { border: none; border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,.08); transition: transform .2s; }
        .program-card:hover { transform: translateY(-4px); }
        .program-icon { font-size: 2.5rem; color: var(--primary); }

        /* Struktur */
        .org-box {
            border: 2px solid var(--primary);
            border-radius: 10px;
            padding: 14px 20px;
            text-align: center;
            background: #fff;
        }
        .org-box.ketua { background: var(--primary); color: #fff; }
        .org-line { width: 2px; height: 30px; background: var(--primary); margin: 0 auto; }
        .org-hline { border-top: 2px solid var(--primary); }

        /* Nilai */
        .nilai-icon { font-size: 2rem; color: var(--accent); }

        /* Footer */
        footer { background: var(--primary); color: rgba(255,255,255,.85); padding: 48px 0 24px; }
        footer a { color: rgba(255,255,255,.7); text-decoration: none; }
        footer a:hover { color: #fff; }
        footer .footer-title { color: #fff; font-weight: 600; margin-bottom: 16px; }
        .footer-bottom { border-top: 1px solid rgba(255,255,255,.15); margin-top: 32px; padding-top: 20px; }
    </style>
</head>
<body>

{{-- ═══ NAVBAR ═══ --}}
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <i class="bi bi-house-heart-fill me-2"></i>Rumah Yatim Baiturrohim
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navHome">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navHome">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                <li class="nav-item"><a class="nav-link" href="#tentang">Tentang Kami</a></li>
                <li class="nav-item"><a class="nav-link" href="#program">Program</a></li>
                <li class="nav-item"><a class="nav-link" href="#struktur">Struktur</a></li>
                <li class="nav-item"><a class="nav-link" href="#kontak">Kontak</a></li>
                <li class="nav-item ms-lg-3">
                    <a class="btn btn-warning fw-semibold px-4" href="{{ route('login') }}">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Login
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

{{-- ═══ HERO ═══ --}}
<section class="hero" style="background: linear-gradient(135deg, rgba(13,59,94,.82) 0%, rgba(26,82,118,.75) 100%), url('{{ asset('images/hero-bg.jpg') }}') center center / cover no-repeat;">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <!--<p class="text-warning fw-semibold mb-2 text-uppercase small letter-spacing-1">Yayasan Sosial Kemanusiaan</p>-->
                <h1 class="mb-4 text-white">Bersama Merawat<br>Generasi Harapan Bangsa</h1>
                <p class="lead mb-4 opacity-90 text-dark">
                    Yayasan Rumah Yatim Baiturrohim hadir sebagai rumah kedua bagi anak-anak yatim,
                    memberikan pendidikan, kasih sayang, dan bekal kehidupan yang layak.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="#program" class="btn btn-warning btn-lg fw-semibold px-4">
                        <i class="bi bi-grid-fill me-2"></i>Program Kami
                    </a>
                    <!--<a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-person-plus me-2"></i>Daftar Orang Tua
                    </a>-->
                </div>
            </div>
            <div class="col-lg-5">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="badge-stat">
                            <h2 class="text-white">{{ $totalAnak }}+</h2>
                            <p class="mb-0 small opacity-75 text-white">Anak Binaan Aktif</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="badge-stat">
                            <h2 class="text-white">{{ $tahunBerdiri }}+</h2>
                            <p class="mb-0 small opacity-75 text-white">Tahun Berdiri</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="badge-stat">
                            <h2 class="text-white">3</h2>
                            <p class="mb-0 small opacity-75 text-white">Jenjang Pendidikan</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="badge-stat">
                            <h2 class="text-white">100%</h2>
                            <p class="mb-0 small opacity-75 text-white">Transparan & Amanah</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══ TENTANG KAMI ═══ --}}
<section id="tentang">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <p class="text-warning fw-semibold text-uppercase small">Tentang Kami</p>
                <h2 class="section-title mb-4">Siapa Kami?</h2>
                <p class="text-muted mb-3">
                    <strong>Yayasan Rumah Yatim Baiturrohim</strong> adalah lembaga sosial nirlaba yang berdiri sejak 1992,
                    berkomitmen memberikan perlindungan, pendidikan, dan pembinaan karakter bagi anak-anak yatim
                    dan dhuafa di wilayah kami.
                </p>
                <p class="text-muted mb-4">
                    <strong>Yayasan Rumah Yatim Baiturrohim</strong>  berdasarkan
akta nomor 92, tanggal 18 April 1992 yang
ditandatangani dan disahkan oleh Nyonya :
Nanny Wahjudi, S.H., Notaris yang berkedudukan di
Tangerang. Adapun para pendiri Yayasan
Baiturrohim adalah : </br> 
- Bapak Drs. Sugim Ernawan</br> 
- Bapak Nurman Abdul Majid</br>
- Bapak Sidik Mansyur </br>
- Bapak Shaleh. </br>
<strong>Yayasan Rumah Yatim Baiturrohim</strong>  memiliki badan sesuai dengan Surat Keputusan Bersama Menteri
Dalam Negeri RI dan Menteri Sosial RI Nomor : 78 Tahun 1993 Nomor : 39/HUK/1993
tanggal 25 September 1993. </br> 
Setelah lebih dari 30 tahun berjalan, 
kepengurusan Yayasan melakukan perubahan Akta Notaris pertama yakni No. 11 tanggal 29 Februari 2024 Notaris
Yulita Roestam, S.H. dan kemudian perubahan Akta Notaris yang kedua dengan Nomor 01. </br>
Tanggal 13 November 2024 Notaris Sherlina Stevani, S.H., M.Kn., Selain perubahan Akta
Notaris, Yayasan Rumah Yatim Baiturrohim juga mendapatkan Pengakuan resmi dari
Kemenkumham dengan nomor AHU-0028439.AH.01.12. Tahun 2024. 

</br>
                </p>
                <!--
                <div class="row g-3">
                    <div class="col-6">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-check-circle-fill text-success mt-1"></i>
                            <span class="small">Terdaftar resmi di Kementerian Sosial</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-check-circle-fill text-success mt-1"></i>
                            <span class="small">Laporan keuangan transparan</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-check-circle-fill text-success mt-1"></i>
                            <span class="small">Pengasuh berpengalaman & bersertifikat</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-check-circle-fill text-success mt-1"></i>
                            <span class="small">Program pendidikan terstruktur</span>
                        </div>
                    </div>
                </div>
-->
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="card h-100 border-0 shadow-sm p-4 text-center">
                            <i class="bi bi-eye-fill fs-1 text-primary mb-3"></i>
                            <h6 class="fw-bold">Visi</h6>
                            <p class="small text-muted mb-0">
                                Membina dan melahirkan generasi muda, khususnya anak-anak yatim, kaum dhuafa, dan anak-anak
                                putus sekolah untuk hafal al qur’an(hafizh), memiliki akhlak Islami, kecerdasan, kemampuan,keterampilan IT, pemahaman agama Islam, 
                                dan semangat wirausaha(entrepreneur) yang mumpuni sesuai bakat yang dimilikinya sehingga mampu mandiri dan menjadi teladan bagi lingkungan
                                dimana mereka berada.
                            </p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card h-100 border-0 shadow-sm p-4 text-center">
                            <i class="bi bi-bullseye fs-1 text-warning mb-3"></i>
                            <h6 class="fw-bold">Misi</h6>
                            <p class="small text-muted mb-0">
                                1. Menjadikan Yayasan Rumah Yatim Baiturrohim sebagai sarana dan media sekaligus tempat
                                    beribadah dan menuntut ilmu bagi setiap muslim, khususnya anak-anak yatim, dhuafa, dan putus sekolah.</br>
                                2. Menjadikan Yayasan Rumah Yatim Baiturrohim sebagai wadah pengembangan Pendidikan
                                    Islam dan Pusat Pelatihan Keterampilan yang dibutuhkan, terutama di bidang Teknologi Informasi dan Komunikasi. </br>
                                3. Mengadakan kegiatan untuk generasi yang berakhlak islami dan memiliki kecerdasan,
                                    kemampuan, dan keterampilan yang mumpuni sesuai bakat yang dimilikinya berbasis kewirausahaan. </br>
                                4. Mendidik dan melatih calon-calon pemimpin di masa mendatang. </br>
                                5. Mengadakan kegiatan untuk generasi muda penghafal al qur’an(hafizh dan hafizhah)
                            </p>
                        </div>
                    </div>
                    
                    <!--<div class="col-12">
                        <div class="card border-0 shadow-sm p-4">
                            <h6 class="fw-bold mb-3"><i class="bi bi-gem text-warning me-2"></i>Nilai-Nilai Kami</h6>
                            <div class="row g-2">
                                @foreach(['Amanah','Ikhlas','Profesional','Transparan','Peduli'] as $nilai)
                                <div class="col-auto">
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">{{ $nilai }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>-->
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══ PROGRAM ═══ --}}
<section id="program">
    <div class="container">
        <div class="text-center mb-5">
            <p class="text-warning fw-semibold text-uppercase small">Apa yang Kami Lakukan</p>
            <h2 class="section-title d-inline-block">Program Unggulan</h2>
        </div>
        <div class="row g-4">
            @php
            $programs = [
                ['icon'=>'bi-book-fill',       'color'=>'primary', 'title'=>'Pendidikan Formal',
                 'desc'=>'Mendukung pendidikan SD, SMP, hingga SMA dengan beasiswa penuh termasuk seragam, buku, dan perlengkapan sekolah.'],
                ['icon'=>'bi-moon-stars-fill',  'color'=>'warning', 'title'=>'Pembinaan Spiritual',
                 'desc'=>'Program tahfidz Al-Qur\'an, kajian agama rutin, dan pembentukan akhlak mulia setiap hari.'],
                ['icon'=>'bi-heart-pulse-fill', 'color'=>'danger',  'title'=>'Kesehatan & Gizi',
                 'desc'=>'Pemeriksaan kesehatan berkala, pemenuhan gizi seimbang, dan jaminan kesehatan bagi seluruh anak binaan.'],
                ['icon'=>'bi-tools',            'color'=>'success', 'title'=>'Keterampilan Hidup',
                 'desc'=>'Pelatihan komputer, kewirausahaan, dan keterampilan vokasional untuk mempersiapkan kemandirian.'],
                ['icon'=>'bi-people-fill',      'color'=>'info',    'title'=>'Pemberdayaan UMKM',
                 'desc'=>'Pendampingan dan pemberdayaan ekonomi ibu anak yatim agar dapat mandiri secara finansial.'],
                ['icon'=>'bi-trophy-fill',      'color'=>'primary', 'title'=>'Pengembangan Bakat',
                 'desc'=>'Fasilitas olahraga, seni, dan ekstrakurikuler untuk mengembangkan potensi setiap anak.'],
            ];
            @endphp
            @foreach($programs as $p)
            <div class="col-md-6 col-lg-4">
                <div class="card program-card h-100 p-4">
                    <div class="program-icon mb-3">
                        <i class="bi {{ $p['icon'] }} text-{{ $p['color'] }}"></i>
                    </div>
                    <h5 class="fw-bold mb-2">{{ $p['title'] }}</h5>
                    <p class="text-muted small mb-0">{{ $p['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══ STRUKTUR ORGANISASI ═══ --}}
<section id="struktur">
    <div class="container">
        <div class="text-center mb-5">
            <p class="text-warning fw-semibold text-uppercase small">Kepengurusan</p>
            <h2 class="section-title d-inline-block">Struktur Organisasi</h2>
        </div>

        {{-- Baris 1: Pembina --}}
        <div class="row justify-content-center mb-2">
            <div class="col-md-3">
                <div class="org-box">
                    <div class="small text-muted">Pembina</div>
                    <div class="fw-bold">Dewan Pembina</div>
                </div>
            </div>
        </div>
        <div class="org-line"></div>

        {{-- Baris 2: Ketua --}}
        <div class="row justify-content-center mb-2">
            <div class="col-md-3">
                <div class="org-box ketua">
                    <div class="small opacity-75">Ketua Yayasan</div>
                    <div class="fw-bold">Ust. Abdul Rohim, S.Kom, M.Pd</div>
                </div>
            </div>
        </div>
        <div class="org-line"></div>

        {{-- Baris 3: Sekretaris & Bendahara --}}
        <div class="row justify-content-center mb-2 position-relative">
            <div class="col-md-8">
                <div class="org-hline my-0" style="margin-top:15px!important;"></div>
            </div>
        </div>
        <div class="row justify-content-center g-3 mb-2">
            <div class="col-md-3">
                <div class="org-box">
                    <div class="small text-muted">Sekretaris</div>
                    <div class="fw-bold">Erika Fitriana, S.Sos</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="org-box">
                    <div class="small text-muted">Bendahara</div>
                    <div class="fw-bold">Nana Suryana, S.Kom</div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center g-3">
            <div class="col-md-3"><div class="org-line"></div></div>
            <div class="col-md-3"><div class="org-line"></div></div>
        </div>

        {{-- Baris 4: Divisi --}}
        <div class="row justify-content-center g-3">
            @foreach(['Divisi Pendidikan','Divisi Kesehatan','Divisi Keuangan','Divisi Humas'] as $div)
            <div class="col-md-2 col-6">
                <div class="org-box" style="border-color:#2e86c1;">
                    <div class="small text-muted fw-semibold">{{ $div }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══ LEGALITAS ═══ --}}
<section>
    <div class="container">
        <div class="text-center mb-5">
            <p class="text-warning fw-semibold text-uppercase small">Legalitas & Perizinan</p>
            <h2 class="section-title d-inline-block">Dokumen Resmi</h2>
        </div>
        <div class="row g-4 justify-content-center">
            @php
            $docs = [
                ['icon'=>'bi-file-earmark-text-fill','title'=>'Akta Notaris','desc'=>'No. 92 th 1992 — Notaris Nanny Wahyudi, S.H'],
                ['icon'=>'bi-building-fill-check',   'title'=>'SK Kemenkumham','desc'=>'AHU-0028439.AH.01.12. Tahun 2024'],
                ['icon'=>'bi-patch-check-fill',      'title'=>'Izin Operasional','desc'=>'Dinas Sosial nomor 460/181 - Lemb. & DS/2014'],
                
            ];
            @endphp
            @foreach($docs as $d)
            <div class="col-md-3 col-6">
                <div class="text-center p-4 border rounded-3 h-100">
                    <i class="bi {{ $d['icon'] }} fs-1 text-primary mb-3 d-block"></i>
                    <h6 class="fw-bold">{{ $d['title'] }}</h6>
                    <p class="small text-muted mb-0">{{ $d['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══ KONTAK ═══ --}}
<section id="kontak">
    <div class="container">
        <div class="text-center mb-5">
            <p class="text-warning fw-semibold text-uppercase small">Hubungi Kami</p>
            <h2 class="section-title d-inline-block">Informasi Kontak</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="d-flex gap-3 align-items-start">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px;height:48px;">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Alamat</h6>
                        <p class="text-muted small mb-0">
                            Jl. H Mean I No. 40 </br>
                            Kel. Karang Timur Kec. Karang Tengah </br>
                            Kota Tangerang Banten 15157
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex gap-3 align-items-start">
                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px;height:48px;">
                        <i class="bi bi-telephone-fill"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Telepon & WhatsApp</h6>
                        <p class="text-muted small mb-0">
                            0877-7401-7804</br>
                            <span class="text-muted">Senin – Sabtu, 08.00 – 17.00</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex gap-3 align-items-start">
                    <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px;height:48px;">
                        <i class="bi bi-envelope-fill"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Media Sosial</h6>
                        <p class="text-muted small mb-0">
                            YouTube : Yayasan Baiturrohim<br>
                            Instagram : @baiturrohim_ciledug<br>
                            
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- CTA --}}
        <div class="row mt-5">
            <div class="col-12">
                <div class="bg-primary text-white rounded-3 p-5 text-center">
                    <h3 class="fw-bold mb-2">Bergabung Bersama Kami</h3>
                    <p class="opacity-90 mb-4">Orang tua / wali anak yatim dapat mendaftar akun untuk memantau perkembangan dan mengisi absensi bulanan.</p>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="{{ route('register') }}" class="btn btn-warning btn-lg fw-semibold px-5">
                            <i class="bi bi-person-plus-fill me-2"></i>Daftar Sekarang
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-5">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══ FOOTER ═══ --}}
<footer>
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <h5 class="footer-title">
                    <i class="bi bi-house-heart-fill me-2"></i>Yayasan Rumah Yatim Baiturrohim
                </h5>
                
                <div class="d-flex gap-3">
                    <!--<a href="#" class="text-white opacity-75 fs-5"><i class="bi bi-facebook"></i></a>-->
                    <a href="https://www.instagram.com/baiturrohim_ciledug/" class="text-white opacity-75 fs-5"><i class="bi bi-instagram"></i></a>
                    <a href="https://www.youtube.com/@baiturrohim_ciledug" class="text-white opacity-75 fs-5"><i class="bi bi-youtube"></i></a>
                    <a href="https://wa.me/6289525295975?text=Assalamu'alaikum, %20saya%20mau%20tanya" class="text-white opacity-75 fs-5"><i class="bi bi-whatsapp"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-6">
                <h6 class="footer-title">Navigasi</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="#tentang">Tentang Kami</a></li>
                    <li class="mb-2"><a href="#program">Program</a></li>
                    <li class="mb-2"><a href="#struktur">Struktur</a></li>
                    <li class="mb-2"><a href="#kontak">Kontak</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-6">
                <h6 class="footer-title">Akun</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="{{ route('login') }}">Login</a></li>
                    <li class="mb-2"><a href="{{ route('register') }}">Daftar Orang Tua</a></li>
                </ul>
            </div>
            <div class="col-lg-4">
                <h6 class="footer-title">Rekening Donasi</h6>
                <div class="small opacity-75">
                    <div class="mb-2">
                        <span class="fw-semibold text-white">Bank Syariah Indonesia</span><br>
                        No. Rek: 7294768763<br>
                        a.n. Rumah Yatim Baiturrohim YYS
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom text-center small opacity-75">
            &copy; {{ date('Y') }} Yayasan Rumah Yatim Baiturrohim. Semua hak dilindungi.
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', e => {
            const target = document.querySelector(a.getAttribute('href'));
            if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth' }); }
        });
    });
</script>
</body>
</html>
