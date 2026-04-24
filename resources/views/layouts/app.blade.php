<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Data Anak Yatim') - Rumah Yatim Baiturrohim</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
                <img src="{{ asset('images/Logo-Yayasan.png') }}"
                     alt="Logo Baiturrohim"
                     style="height:40px;width:auto;object-fit:contain;">
                <span>Rumah Yatim Baiturrohim</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth

                    {{-- Dashboard --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}"
                           href="{{ route('dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>

                    {{-- Admin & Staff --}}
                    @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('anak-yatim*') ? 'active' : '' }}"
                               href="{{ url('/anak-yatim') }}">
                                <i class="bi bi-people-fill"></i> Data Anak Yatim
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ request()->is('absensi*') ? 'active' : '' }}"
                               href="#" id="absensiDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-calendar-check"></i> Absensi
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="absensiDropdown">
                                <li><a class="dropdown-item" href="{{ route('absensi.create') }}">
                                    <i class="bi bi-pencil-square"></i> Submit Absensi
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('absensi.approval') }}">
                                    <i class="bi bi-clipboard2-check"></i> Approval Absensi
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-warning" href="{{ route('absensi.tidak-hadir') }}">
                                    <i class="bi bi-exclamation-triangle"></i> Anak Tidak Hadir
                                </a></li>
                            </ul>
                        </li>
                    @endif

                    {{-- Orang Tua --}}
                    @if(auth()->user()->isOrangTua())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('absensi*') ? 'active' : '' }}"
                               href="{{ route('absensi.create') }}">
                                <i class="bi bi-calendar-check"></i> Absensi
                            </a>
                        </li>
                        @if(auth()->user()->anakYatim)
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('anak-yatim*') ? 'active' : '' }}"
                               href="{{ route('anak-yatim.show', auth()->user()->anakYatim) }}">
                                <i class="bi bi-person-fill"></i> Data Anak
                            </a>
                        </li>
                        @endif
                    @endif

                    {{-- Keuangan: Admin & Bendahara --}}
                    @if(auth()->user()->isAdmin() || auth()->user()->isBendahara())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ request()->is('keuangan*') || request()->is('transaksi*') ? 'active' : '' }}"
                               href="#" id="keuanganDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-cash-coin"></i> Keuangan
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="keuanganDropdown">
                                <li><a class="dropdown-item" href="{{ route('keuangan.dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> Dashboard Keuangan
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('transaksi.index') }}">
                                    <i class="bi bi-list-ul"></i> Transaksi
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('transaksi.create') }}">
                                    <i class="bi bi-plus-circle"></i> Tambah Transaksi
                                </a></li>
                            </ul>
                        </li>
                    @endif

                    {{-- Laporan: semua kecuali orang tua --}}
                    @if(!auth()->user()->isOrangTua())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('laporan*') ? 'active' : '' }}"
                               href="{{ url('/laporan') }}">
                                <i class="bi bi-file-earmark-text"></i> Laporan
                            </a>
                        </li>
                    @endif

                    {{-- Manajemen User: Admin --}}
                    @if(auth()->user()->isAdmin())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ request()->is('users*') ? 'active' : '' }}"
                               href="#" id="userMgmtDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-gear"></i> Manajemen User
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="userMgmtDropdown">
                                <li><a class="dropdown-item" href="{{ route('users.index') }}">
                                    <i class="bi bi-list-ul"></i> Daftar User
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('users.create') }}">
                                    <i class="bi bi-person-plus"></i> Tambah User
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('users.orang-tua.create') }}">
                                    <i class="bi bi-person-heart"></i> Buat Akun Orang Tua
                                </a></li>
                            </ul>
                        </li>
                    @endif

                    {{-- Approval Orang Tua: Admin & Staff --}}
                    @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                        <li class="nav-item">
                            @php
                                $pendingOrangTua = \App\Models\User::where('role','orang_tua')
                                    ->where('status_akun','pending')->count();
                            @endphp
                            <a class="nav-link {{ request()->is('users/approval*') ? 'active' : '' }}"
                               href="{{ route('users.approval-orang-tua') }}">
                                <i class="bi bi-person-check"></i> Approval Orang Tua
                                @if($pendingOrangTua > 0)
                                    <span class="badge bg-danger rounded-pill">{{ $pendingOrangTua }}</span>
                                @endif
                            </a>
                        </li>
                    @endif

                    {{-- Profil --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->is('profile*') ? 'active' : '' }}"
                           href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
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
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>

                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4">
        <div class="container">
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
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-light py-4 mt-5">
        <div class="container text-center text-muted">
            <p class="mb-0">&copy; {{ date('Y') }} Rumah Yatim Baiturrohim. Sistem Data Anak Yatim.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
