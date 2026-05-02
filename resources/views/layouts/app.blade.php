<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Data Anak Yatim') - Rumah Yatim Baiturrohim</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        /* ── SIDEBAR LAYOUT ─────────────────────────────────────────── */
        :root {
            --sidebar-w: 240px;
            --sidebar-collapsed-w: 0px;
            --topbar-h: 56px;
            --sidebar-bg: #1e2a3a;
            --sidebar-hover: rgba(255,255,255,0.07);
            --sidebar-active: rgba(255,255,255,0.13);
            --sidebar-text: rgba(255,255,255,0.82);
            --sidebar-text-muted: rgba(255,255,255,0.45);
            --sidebar-border: rgba(255,255,255,0.08);
            --transition: all 0.25s ease;
        }

        body { margin: 0; background: #f4f6f9; font-family: inherit; }

        /* ── TOP BAR ────────────────────────────────────────────────── */
        .topbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: var(--topbar-h);
            background: #0d6efd;
            display: flex;
            align-items: center;
            padding: 0 16px;
            z-index: 1040;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            gap: 12px;
        }

        .topbar-toggle {
            background: none;
            border: none;
            color: #fff;
            font-size: 1.3rem;
            cursor: pointer;
            padding: 6px 8px;
            border-radius: 6px;
            transition: var(--transition);
            flex-shrink: 0;
            line-height: 1;
        }
        .topbar-toggle:hover { background: rgba(255,255,255,0.15); }

        .topbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: #fff;
            font-weight: 700;
            font-size: 0.95rem;
            flex: 1;
            min-width: 0;
        }
        .topbar-brand img {
            height: 34px;
            width: auto;
            object-fit: contain;
            flex-shrink: 0;
        }
        .topbar-brand span {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        /* ── SIDEBAR ────────────────────────────────────────────────── */
        .sidebar {
            position: fixed;
            top: var(--topbar-h);
            left: 0;
            bottom: 0;
            width: var(--sidebar-w);
            background: var(--sidebar-bg);
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1030;
            transition: var(--transition);
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.1) transparent;
        }
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 4px; }

        .sidebar.collapsed {
            width: 0;
        }

        /* ── SIDEBAR CONTENT ────────────────────────────────────────── */
        .sidebar-inner {
            width: var(--sidebar-w);
            padding: 12px 0 24px;
        }

        /* Section header */
        .sidebar-section-title {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--sidebar-text-muted);
            padding: 16px 18px 6px;
            white-space: nowrap;
        }

        /* Nav item */
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 18px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 0;
            transition: var(--transition);
            white-space: nowrap;
            position: relative;
        }
        .sidebar-link:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }
        .sidebar-link.active {
            background: var(--sidebar-active);
            color: #fff;
            border-left: 3px solid #0d6efd;
        }
        .sidebar-link .bi {
            font-size: 1rem;
            flex-shrink: 0;
            width: 18px;
            text-align: center;
        }

        /* Collapsible group */
        .sidebar-group-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 18px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            white-space: nowrap;
            transition: var(--transition);
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }
        .sidebar-group-toggle:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }
        .sidebar-group-toggle .bi-main { font-size: 1rem; flex-shrink: 0; width: 18px; text-align: center; }
        .sidebar-group-toggle .chevron {
            margin-left: auto;
            font-size: 0.7rem;
            transition: transform 0.2s;
            flex-shrink: 0;
        }
        .sidebar-group-toggle[aria-expanded="true"] .chevron {
            transform: rotate(180deg);
        }
        .sidebar-group-toggle.active-group { color: #fff; }

        .sidebar-sub {
            background: rgba(0,0,0,0.15);
        }
        .sidebar-sub .sidebar-link {
            padding-left: 46px;
            font-size: 0.84rem;
            font-weight: 400;
        }

        .sidebar-divider {
            border-top: 1px solid var(--sidebar-border);
            margin: 8px 0;
        }

        /* Badge in sidebar */
        .sidebar-badge {
            margin-left: auto;
            background: #dc3545;
            color: #fff;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 50px;
            flex-shrink: 0;
        }

        /* ── MAIN WRAPPER ───────────────────────────────────────────── */
        .main-wrapper {
            margin-top: var(--topbar-h);
            margin-left: var(--sidebar-w);
            transition: var(--transition);
            min-height: calc(100vh - var(--topbar-h));
            display: flex;
            flex-direction: column;
        }
        .main-wrapper.sidebar-collapsed {
            margin-left: 0;
        }

        /* No sidebar (orang tua, bendahara, guest) */
        .main-wrapper.no-sidebar {
            margin-left: 0;
        }

        .main-content {
            flex: 1;
            padding: 24px 24px 16px;
        }

        /* ── TOPBAR USER DROPDOWN ───────────────────────────────────── */
        .topbar .dropdown-menu {
            margin-top: 8px;
            min-width: 200px;
        }
        .topbar .nav-link {
            color: rgba(255,255,255,0.9);
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .topbar .nav-link:hover { background: rgba(255,255,255,0.15); color: #fff; }

        /* ── OVERLAY (mobile) ───────────────────────────────────────── */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 1029;
        }
        .sidebar-overlay.show { display: block; }

        /* ── FOOTER ─────────────────────────────────────────────────── */
        .main-footer {
            background: #fff;
            border-top: 1px solid #e9ecef;
            padding: 14px 24px;
            text-align: center;
            font-size: 0.8rem;
            color: #6c757d;
        }

        /* ── RESPONSIVE ─────────────────────────────────────────────── */
        @media (max-width: 991px) {
            .sidebar {
                width: 0;
            }
            .sidebar.mobile-open {
                width: var(--sidebar-w);
            }
            .main-wrapper {
                margin-left: 0 !important;
            }
        }
    </style>

    @stack('styles')
</head>
<body>

@php
    $hasSidebar = auth()->check() && (
        auth()->user()->isAdmin() ||
        auth()->user()->isStaff() ||
        auth()->user()->isBendahara()
    );
    $pendingOrangTua = (auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isStaff()))
        ? \App\Models\User::where('role','orang_tua')->where('status_akun','pending')->count()
        : 0;
@endphp

{{-- ── TOP BAR ──────────────────────────────────────────────────────── --}}
<header class="topbar">
    @if($hasSidebar)
    <button class="topbar-toggle" id="sidebarToggle" title="Toggle Sidebar" aria-label="Toggle Sidebar">
        <i class="bi bi-list"></i>
    </button>
    @endif

    <a href="{{ auth()->check() ? route('dashboard') : route('home') }}" class="topbar-brand">
        <img src="{{ asset('images/Logo-Yayasan.png') }}" alt="Logo Baiturrohim">
        <span>Rumah Yatim Baiturrohim</span>
    </a>

    <div class="topbar-right">
        @auth
        {{-- Notifikasi pending (admin & staff) --}}
        @if($pendingOrangTua > 0)
        <a href="{{ route('users.approval-orang-tua') }}"
           class="nav-link position-relative" title="Approval Orang Tua">
            <i class="bi bi-bell-fill text-warning"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                  style="font-size:.6rem;">{{ $pendingOrangTua }}</span>
        </a>
        @endif

        {{-- User dropdown --}}
        <div class="dropdown">
            <a class="nav-link dropdown-toggle" href="#"
               id="topUserDropdown" role="button" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle"></i>
                <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="topUserDropdown">
                <li><h6 class="dropdown-header">{{ auth()->user()->name }}</h6></li>
                <li><span class="dropdown-item-text text-muted small">
                    <i class="bi bi-shield-check"></i>
                    {{ ucfirst(auth()->user()->role) }}
                </span></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="{{ route('profile.show') }}">
                    <i class="bi bi-person"></i> Profil Saya
                </a></li>
                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                    <i class="bi bi-pencil"></i> Edit Profil
                </a></li>
                <li><a class="dropdown-item" href="{{ route('profile.change-password') }}">
                    <i class="bi bi-key"></i> Ubah Password
                </a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
        @endauth
    </div>
</header>

{{-- ── SIDEBAR (admin, staff, bendahara) ──────────────────────────────── --}}
@if($hasSidebar)
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-inner">

        {{-- === UTAMA === --}}
        <div class="sidebar-section-title">Utama</div>

        <a href="{{ route('dashboard') }}"
           class="sidebar-link {{ request()->is('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        {{-- === DATA ANAK YATIM (admin & staff) === --}}
        @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
        <div class="sidebar-divider"></div>
        <div class="sidebar-section-title">Data Anak Yatim</div>

        <a href="{{ url('/anak-yatim') }}"
           class="sidebar-link {{ request()->is('anak-yatim') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> Daftar Anak Yatim
        </a>

        @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
        <a href="{{ route('anak-yatim.create') }}"
           class="sidebar-link {{ request()->routeIs('anak-yatim.create') ? 'active' : '' }}">
            <i class="bi bi-person-plus-fill"></i> Tambah Anak Yatim
        </a>
        @endif

        {{-- Absensi collapsible --}}
        <button class="sidebar-group-toggle {{ request()->is('absensi*') ? 'active-group' : '' }}"
                data-bs-toggle="collapse" data-bs-target="#absensiMenu"
                aria-expanded="{{ request()->is('absensi*') ? 'true' : 'false' }}">
            <i class="bi bi-calendar-check bi-main"></i>
            Absensi
            <i class="bi bi-chevron-down chevron"></i>
        </button>
        <div class="collapse sidebar-sub {{ request()->is('absensi*') ? 'show' : '' }}" id="absensiMenu">
            <a href="{{ route('absensi.create') }}"
               class="sidebar-link {{ request()->routeIs('absensi.create') ? 'active' : '' }}">
                <i class="bi bi-pencil-square"></i> Submit Absensi
            </a>
            <a href="{{ route('absensi.approval') }}"
               class="sidebar-link {{ request()->routeIs('absensi.approval') ? 'active' : '' }}">
                <i class="bi bi-clipboard2-check"></i> Approval Absensi
            </a>
            <a href="{{ route('absensi.tidak-hadir') }}"
               class="sidebar-link {{ request()->routeIs('absensi.tidak-hadir') ? 'active' : '' }}">
                <i class="bi bi-exclamation-triangle text-warning"></i> Anak Tidak Hadir
            </a>
        </div>
        @endif

        {{-- === KEUANGAN (admin & bendahara) === --}}
        @if(auth()->user()->isAdmin() || auth()->user()->isBendahara())
        <div class="sidebar-divider"></div>
        <div class="sidebar-section-title">Keuangan</div>

        <a href="{{ route('keuangan.dashboard') }}"
           class="sidebar-link {{ request()->routeIs('keuangan.dashboard') ? 'active' : '' }}">
            <i class="bi bi-graph-up-arrow"></i> Dashboard Keuangan
        </a>

        <a href="{{ route('transaksi.index') }}"
           class="sidebar-link {{ request()->is('transaksi') ? 'active' : '' }}">
            <i class="bi bi-list-ul"></i> Transaksi
        </a>

        <a href="{{ route('transaksi.create') }}"
           class="sidebar-link {{ request()->routeIs('transaksi.create') ? 'active' : '' }}">
            <i class="bi bi-plus-circle"></i> Tambah Transaksi
        </a>
        @endif

        {{-- === LAPORAN === --}}
        <div class="sidebar-divider"></div>
        <div class="sidebar-section-title">Laporan</div>

        <a href="{{ url('/laporan') }}"
           class="sidebar-link {{ request()->is('laporan*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text"></i> Laporan Anak Yatim
        </a>

        {{-- === KONTEN (admin & staff) === --}}
        @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
        <div class="sidebar-divider"></div>
        <div class="sidebar-section-title">Konten</div>

        <a href="{{ route('berita.index') }}"
           class="sidebar-link {{ request()->is('berita*') ? 'active' : '' }}">
            <i class="bi bi-newspaper"></i> Manajemen Berita
        </a>

        <a href="{{ route('users.approval-orang-tua') }}"
           class="sidebar-link {{ request()->is('users/approval*') ? 'active' : '' }}">
            <i class="bi bi-person-check"></i> Approval Orang Tua
            @if($pendingOrangTua > 0)
                <span class="sidebar-badge">{{ $pendingOrangTua }}</span>
            @endif
        </a>
        @endif

        {{-- === MANAJEMEN USER (admin only) === --}}
        @if(auth()->user()->isAdmin())
        <div class="sidebar-divider"></div>
        <div class="sidebar-section-title">Manajemen User</div>

        <a href="{{ route('users.index') }}"
           class="sidebar-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Daftar User
        </a>

        <a href="{{ route('users.create') }}"
           class="sidebar-link {{ request()->routeIs('users.create') ? 'active' : '' }}">
            <i class="bi bi-person-plus"></i> Tambah User
        </a>

        <a href="{{ route('users.orang-tua.create') }}"
           class="sidebar-link {{ request()->routeIs('users.orang-tua.create') ? 'active' : '' }}">
            <i class="bi bi-person-heart"></i> Buat Akun Orang Tua
        </a>
        @endif

    </div>
</aside>
@endif

{{-- ── MAIN WRAPPER ─────────────────────────────────────────────────── --}}
<div class="main-wrapper {{ $hasSidebar ? '' : 'no-sidebar' }}" id="mainWrapper">

    <main class="main-content">

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle-fill"></i> {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="bi bi-info-circle-fill"></i> {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="main-footer">
        &copy; {{ date('Y') }} Rumah Yatim Baiturrohim. Sistem Data Anak Yatim.
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')

<script>
(function () {
    const sidebar   = document.getElementById('sidebar');
    const wrapper   = document.getElementById('mainWrapper');
    const toggle    = document.getElementById('sidebarToggle');
    const overlay   = document.getElementById('sidebarOverlay');

    if (!sidebar || !toggle) return;

    const STORAGE_KEY = 'sidebarCollapsed';
    const isMobile    = () => window.innerWidth < 992;

    // Restore state on desktop
    function applyState() {
        if (isMobile()) {
            sidebar.classList.remove('collapsed');
            sidebar.classList.remove('mobile-open');
            wrapper.classList.remove('sidebar-collapsed');
            overlay.classList.remove('show');
        } else {
            const collapsed = localStorage.getItem(STORAGE_KEY) === '1';
            sidebar.classList.toggle('collapsed', collapsed);
            wrapper.classList.toggle('sidebar-collapsed', collapsed);
        }
    }

    applyState();
    window.addEventListener('resize', applyState);

    toggle.addEventListener('click', function () {
        if (isMobile()) {
            const open = sidebar.classList.toggle('mobile-open');
            overlay.classList.toggle('show', open);
        } else {
            const collapsed = sidebar.classList.toggle('collapsed');
            wrapper.classList.toggle('sidebar-collapsed', collapsed);
            localStorage.setItem(STORAGE_KEY, collapsed ? '1' : '0');
        }
    });

    overlay.addEventListener('click', function () {
        sidebar.classList.remove('mobile-open');
        overlay.classList.remove('show');
    });
})();
</script>

{{-- Tombol Donasi Melayang --}}
<a href="{{ route('donasi.create') }}"
   class="floating-donate-btn"
   title="Donasi Sekarang"
   aria-label="Donasi Sekarang">
    <span style="font-size:1.2rem;line-height:1;">💝</span>
    <span>Donasi</span>
</a>

<style>
    .floating-donate-btn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1050;
        display: flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #d4a017, #e8b820);
        color: #fff;
        text-decoration: none;
        padding: 13px 20px 13px 16px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.9rem;
        box-shadow: 0 6px 24px rgba(212,160,23,0.5);
        transition: all 0.3s ease;
        white-space: nowrap;
    }
    .floating-donate-btn:hover {
        transform: translateY(-4px) scale(1.04);
        box-shadow: 0 12px 36px rgba(212,160,23,0.6);
        color: #fff;
        text-decoration: none;
    }
    .floating-donate-btn::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 50px;
        background: inherit;
        z-index: -1;
        animation: donatePulse 2.5s ease-out infinite;
    }
    @keyframes donatePulse {
        0%   { transform: scale(1); opacity: 0.7; }
        70%  { transform: scale(1.25); opacity: 0; }
        100% { transform: scale(1.25); opacity: 0; }
    }
    @media (max-width: 576px) {
        .floating-donate-btn { bottom: 20px; right: 20px; padding: 11px 14px; font-size: 0.85rem; }
    }
</style>

</body>
</html>
