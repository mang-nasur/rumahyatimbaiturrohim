<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AnakYatimController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardKeuanganController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Halaman publik (company profile) — / dan /tentang sama-sama menampilkan beranda
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang', fn() => redirect()->route('home'));

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    
    // Apply throttle only in non-testing environment
    $loginRoute = Route::post('/login', [AuthController::class, 'login']);
    if (app()->environment() !== 'testing') {
        $loginRoute->middleware('throttle:5,1');
    }

    // Pendaftaran mandiri orang tua / wali
    Route::get('/daftar', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/daftar', [RegisterController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard — hanya untuk yang sudah login
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Dashboard Keuangan
Route::middleware('auth')->group(function () {
    Route::get('/keuangan/dashboard', [DashboardKeuanganController::class, 'index'])->name('keuangan.dashboard');
});

// CRUD Anak Yatim
Route::middleware('auth')->group(function () {
    // Index: hanya admin, bendahara, staff
    Route::get('/anak-yatim', [AnakYatimController::class, 'index'])
        ->middleware('role:admin,bendahara,staff')
        ->name('anak-yatim.index');

    // Export (admin, bendahara, staff)
    Route::middleware('role:admin,bendahara,staff')->group(function () {
        Route::get('/anak-yatim/export/excel', [AnakYatimController::class, 'exportExcel'])->name('anak-yatim.export.excel');
        Route::get('/anak-yatim/export/pdf', [AnakYatimController::class, 'exportPdf'])->name('anak-yatim.export.pdf');
    });

    // Create & store: admin dan staff saja — HARUS sebelum route wildcard {anak_yatim}
    Route::middleware('role:admin,staff')->group(function () {
        Route::get('/anak-yatim/create', [AnakYatimController::class, 'create'])->name('anak-yatim.create');
        Route::post('/anak-yatim', [AnakYatimController::class, 'store'])->name('anak-yatim.store');
        Route::delete('/anak-yatim/{anak_yatim}', [AnakYatimController::class, 'destroy'])->name('anak-yatim.destroy');
    });

    // Show, edit, update: semua role termasuk orang_tua (controller yang batasi per anak)
    Route::get('/anak-yatim/{anak_yatim}', [AnakYatimController::class, 'show'])->name('anak-yatim.show');
    Route::get('/anak-yatim/{anak_yatim}/edit', [AnakYatimController::class, 'edit'])->name('anak-yatim.edit');
    Route::put('/anak-yatim/{anak_yatim}', [AnakYatimController::class, 'update'])->name('anak-yatim.update');
});

// CRUD Transaksi Keuangan
Route::middleware('auth')->group(function () {
    // Read-only routes for laporan (admin, bendahara, staff)
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    
    // CRUD routes (admin, bendahara only)
    Route::middleware('role:admin,bendahara')->group(function () {
        Route::get('/transaksi/create', [TransaksiController::class, 'create'])->name('transaksi.create');
        Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
        Route::get('/transaksi/{transaksi}/edit', [TransaksiController::class, 'edit'])->name('transaksi.edit');
        Route::put('/transaksi/{transaksi}', [TransaksiController::class, 'update'])->name('transaksi.update');
        Route::delete('/transaksi/{transaksi}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');
    });
    
    Route::get('/transaksi/{transaksi}', [TransaksiController::class, 'show'])->name('transaksi.show');
});

// Laporan
Route::middleware('auth')->group(function () {
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::post('/laporan/preview', [LaporanController::class, 'preview'])->name('laporan.preview');
    Route::post('/laporan/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
    Route::post('/laporan/excel', [LaporanController::class, 'exportExcel'])->name('laporan.excel');
});

// User Management (Admin only)
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Route statis HARUS didaftarkan sebelum resource route
    // agar tidak tertangkap oleh users/{user}
    Route::get('/users/orang-tua/create', [UserController::class, 'createOrangTua'])->name('users.orang-tua.create');
    Route::post('/users/orang-tua', [UserController::class, 'storeOrangTua'])->name('users.orang-tua.store');

    Route::resource('users', UserController::class)->except(['show']);
    Route::get('/users/{user}/reset-password', [UserController::class, 'showResetPasswordForm'])->name('users.reset-password');
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password.update');
});

// Approval pendaftaran orang tua (admin & staff)
// Juga harus sebelum resource route users/{user}
Route::middleware(['auth', 'role:admin,staff'])->group(function () {
    Route::get('/users/approval-orang-tua', [RegisterController::class, 'indexApproval'])->name('users.approval-orang-tua');
    Route::post('/users/{user}/approve-orang-tua', [RegisterController::class, 'approve'])->name('users.approve-orang-tua');
    Route::post('/users/{user}/reject-orang-tua', [RegisterController::class, 'reject'])->name('users.reject-orang-tua');
});

// Profile Management (Authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'showChangePasswordForm'])->name('profile.change-password');
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password.update');
});

// Absensi
Route::middleware('auth')->group(function () {
    // Submit absensi (semua user yang login)
    Route::get('/absensi/submit', [AbsensiController::class, 'create'])->name('absensi.create');
    Route::post('/absensi/submit', [AbsensiController::class, 'store'])->name('absensi.store');

    // Riwayat absensi per anak
    Route::get('/absensi/riwayat/{anak_yatim}', [AbsensiController::class, 'riwayat'])->name('absensi.riwayat');

    // Approval & monitoring (admin dan staff)
    Route::middleware('role:admin,staff')->group(function () {
        Route::get('/absensi/approval', [AbsensiController::class, 'indexApproval'])->name('absensi.approval');
        Route::post('/absensi/{absensi}/approve', [AbsensiController::class, 'approve'])->name('absensi.approve');
        Route::post('/absensi/{absensi}/reject', [AbsensiController::class, 'reject'])->name('absensi.reject');
        Route::post('/absensi/approve-all', [AbsensiController::class, 'approveAll'])->name('absensi.approve-all');
        Route::get('/absensi/tidak-hadir', [AbsensiController::class, 'tidakHadir'])->name('absensi.tidak-hadir');
    });
});
