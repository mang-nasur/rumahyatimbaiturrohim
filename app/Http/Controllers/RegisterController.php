<?php

namespace App\Http\Controllers;

use App\Models\AnakYatim;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Tampilkan form pendaftaran orang tua / wali.
     */
    public function showForm(): View
    {
        // Hanya anak yatim aktif yang belum punya akun orang tua
        $anakYatimList = AnakYatim::aktif()
            ->whereDoesntHave('orangTuaUser')
            ->orderBy('nama_lengkap')
            ->get();

        return view('auth.register', compact('anakYatimList'));
    }

    /**
     * Proses pendaftaran orang tua / wali.
     * Akun langsung berstatus 'pending' — menunggu approval pengurus.
     */
    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|string|min:8|confirmed',
            'anak_yatim_id' => 'required|exists:anak_yatim,id',
        ], [
            'name.required'          => 'Nama lengkap wajib diisi.',
            'email.required'         => 'Email wajib diisi.',
            'email.email'            => 'Format email tidak valid.',
            'email.unique'           => 'Email sudah terdaftar.',
            'password.required'      => 'Password wajib diisi.',
            'password.min'           => 'Password minimal 8 karakter.',
            'password.confirmed'     => 'Konfirmasi password tidak cocok.',
            'anak_yatim_id.required' => 'Pilih nama anak yatim Anda.',
            'anak_yatim_id.exists'   => 'Anak yatim tidak ditemukan.',
        ]);

        // Pastikan anak yatim yang dipilih belum punya akun orang tua
        $sudahAda = User::where('anak_yatim_id', $request->anak_yatim_id)
            ->where('role', 'orang_tua')
            ->exists();

        if ($sudahAda) {
            return back()
                ->withInput()
                ->withErrors(['anak_yatim_id' => 'Anak yatim ini sudah memiliki akun orang tua yang terdaftar.']);
        }

        User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'role'          => 'orang_tua',
            'anak_yatim_id' => $request->anak_yatim_id,
            'status_akun'   => 'pending',
        ]);

        return redirect()->route('login')
            ->with('success', 'Pendaftaran berhasil! Akun Anda sedang menunggu persetujuan pengurus. Anda akan dihubungi setelah akun disetujui.');
    }

    // ─── Admin/Staff: Approval Pendaftaran ───────────────────────────────────

    /**
     * Daftar akun orang tua yang menunggu approval.
     */
    public function indexApproval(Request $request): View
    {
        $status = $request->get('status', 'pending');

        $query = User::with('anakYatim', 'approvedByUser')
            ->where('role', 'orang_tua');

        if ($status !== 'semua') {
            $query->where('status_akun', $status);
        }

        $users = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        $totalPending  = User::where('role', 'orang_tua')->where('status_akun', 'pending')->count();
        $totalAktif    = User::where('role', 'orang_tua')->where('status_akun', 'aktif')->count();
        $totalDitolak  = User::where('role', 'orang_tua')->where('status_akun', 'ditolak')->count();

        return view('users.approval-orang-tua', compact(
            'users', 'status',
            'totalPending', 'totalAktif', 'totalDitolak'
        ));
    }

    /**
     * Setujui pendaftaran orang tua.
     */
    public function approve(User $user): RedirectResponse
    {
        if (!$user->isOrangTua()) {
            return back()->with('error', 'User ini bukan orang tua.');
        }

        $user->update([
            'status_akun'        => 'aktif',
            'catatan_penolakan'  => null,
            'approved_by'        => auth()->id(),
            'approved_at'        => now(),
        ]);

        return back()->with('success', "Akun {$user->name} berhasil disetujui. Orang tua sudah bisa login.");
    }

    /**
     * Tolak pendaftaran orang tua.
     */
    public function reject(Request $request, User $user): RedirectResponse
    {
        if (!$user->isOrangTua()) {
            return back()->with('error', 'User ini bukan orang tua.');
        }

        $request->validate([
            'catatan_penolakan' => 'nullable|string|max:500',
        ]);

        $user->update([
            'status_akun'       => 'ditolak',
            'catatan_penolakan' => $request->catatan_penolakan,
            'approved_by'       => auth()->id(),
            'approved_at'       => now(),
        ]);

        return back()->with('success', "Pendaftaran {$user->name} ditolak.");
    }
}
