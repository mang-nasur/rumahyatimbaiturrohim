<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AnakYatim;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the users with optional role filter.
     */
    public function index(Request $request)
    {
        $query = User::with('anakYatim')->orderBy('created_at', 'desc');

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->paginate(15)->withQueryString();

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new internal user (admin/staff/bendahara).
     */
    public function create()
    {
        $roles = ['admin', 'bendahara', 'staff'];

        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(StoreUserRequest $request)
    {
        User::create($request->validated());

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Show the form for creating an orang tua / wali user.
     */
    public function createOrangTua()
    {
        // Ambil anak yatim aktif yang belum punya akun orang tua
        $anakYatimList = AnakYatim::aktif()
            ->whereDoesntHave('orangTuaUser')
            ->orderBy('nama_lengkap')
            ->get();

        return view('users.create-orang-tua', compact('anakYatimList'));
    }

    /**
     * Store a newly created orang tua user.
     */
    public function storeOrangTua(StoreUserRequest $request)
    {
        // Pastikan role yang dikirim adalah orang_tua
        $data = $request->validated();
        $data['role'] = 'orang_tua';

        User::create($data);

        return redirect()->route('users.index', ['role' => 'orang_tua'])
            ->with('success', 'Akun orang tua / wali berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = ['admin', 'bendahara', 'staff', 'orang_tua'];

        // Untuk orang tua, sediakan daftar anak yatim
        $anakYatimList = null;
        if ($user->isOrangTua()) {
            $anakYatimList = AnakYatim::aktif()
                ->where(function ($q) use ($user) {
                    // Tampilkan anak yang belum punya akun orang tua, ATAU anak milik user ini
                    $q->whereDoesntHave('orangTuaUser')
                      ->orWhere('id', $user->anak_yatim_id);
                })
                ->orderBy('nama_lengkap')
                ->get();
        }

        return view('users.edit', compact('user', 'roles', 'anakYatimList'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diperbarui');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus');
    }

    /**
     * Show the form for resetting user password.
     */
    public function showResetPasswordForm(User $user)
    {
        return view('users.reset-password', compact('user'));
    }

    /**
     * Reset user password.
     */
    public function resetPassword(ResetPasswordRequest $request, User $user)
    {
        $user->update([
            'password' => $request->validated()['password'],
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Password berhasil direset');
    }
}
