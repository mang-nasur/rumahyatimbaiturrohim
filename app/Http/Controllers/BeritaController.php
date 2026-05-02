<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    // ─── Admin: Daftar semua berita ───────────────────────────────────────────

    public function index(Request $request)
    {
        $query = Berita::with('penulis')->orderByDesc('created_at');

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $beritaList = $query->paginate(10)->withQueryString();

        return view('berita.index', compact('beritaList'));
    }

    // ─── Admin: Form tambah ───────────────────────────────────────────────────

    public function create()
    {
        return view('berita.create');
    }

    // ─── Admin: Simpan berita baru ────────────────────────────────────────────

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'              => 'required|string|max:200',
            'ringkasan'          => 'nullable|string|max:300',
            'isi'                => 'required|string|min:10',
            'kategori'           => 'required|in:' . implode(',', Berita::KATEGORI),
            'status'             => 'required|in:draft,published',
            'tanggal_publikasi'  => 'nullable|date',
            'foto'               => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'judul.required'     => 'Judul berita wajib diisi.',
            'judul.max'          => 'Judul maksimal 200 karakter.',
            'isi.required'       => 'Isi berita wajib diisi.',
            'isi.min'            => 'Isi berita minimal 10 karakter.',
            'kategori.required'  => 'Kategori wajib dipilih.',
            'kategori.in'        => 'Kategori tidak valid.',
            'status.required'    => 'Status wajib dipilih.',
            'foto.image'         => 'File harus berupa gambar.',
            'foto.mimes'         => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'foto.max'           => 'Ukuran gambar maksimal 2MB.',
        ]);

        // Upload foto
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('berita', 'public');
        }

        // Set tanggal publikasi otomatis jika status published dan tanggal kosong
        $tanggal = $validated['tanggal_publikasi'] ?? null;
        if ($validated['status'] === 'published' && !$tanggal) {
            $tanggal = now()->toDateString();
        }

        Berita::create([
            'judul'             => $validated['judul'],
            'slug'              => Berita::generateSlug($validated['judul']),
            'ringkasan'         => $validated['ringkasan'] ?? null,
            'isi'               => $validated['isi'],
            'kategori'          => $validated['kategori'],
            'status'            => $validated['status'],
            'tanggal_publikasi' => $tanggal,
            'foto'              => $fotoPath,
            'user_id'           => Auth::id(),
        ]);

        return redirect()->route('berita.index')
            ->with('success', 'Berita berhasil ditambahkan.');
    }

    // ─── Admin: Form edit ─────────────────────────────────────────────────────

    public function edit(Berita $beritum)
    {
        return view('berita.edit', ['berita' => $beritum]);
    }

    // ─── Admin: Update berita ─────────────────────────────────────────────────

    public function update(Request $request, Berita $beritum)
    {
        $validated = $request->validate([
            'judul'              => 'required|string|max:200',
            'ringkasan'          => 'nullable|string|max:300',
            'isi'                => 'required|string|min:10',
            'kategori'           => 'required|in:' . implode(',', Berita::KATEGORI),
            'status'             => 'required|in:draft,published',
            'tanggal_publikasi'  => 'nullable|date',
            'foto'               => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'judul.required'     => 'Judul berita wajib diisi.',
            'judul.max'          => 'Judul maksimal 200 karakter.',
            'isi.required'       => 'Isi berita wajib diisi.',
            'isi.min'            => 'Isi berita minimal 10 karakter.',
            'kategori.required'  => 'Kategori wajib dipilih.',
            'foto.image'         => 'File harus berupa gambar.',
            'foto.mimes'         => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'foto.max'           => 'Ukuran gambar maksimal 2MB.',
        ]);

        // Upload foto baru jika ada
        $fotoPath = $beritum->foto;
        if ($request->hasFile('foto')) {
            if ($beritum->foto) {
                Storage::disk('public')->delete($beritum->foto);
            }
            $fotoPath = $request->file('foto')->store('berita', 'public');
        }

        // Hapus foto jika diminta
        if ($request->boolean('hapus_foto') && $beritum->foto) {
            Storage::disk('public')->delete($beritum->foto);
            $fotoPath = null;
        }

        $tanggal = $validated['tanggal_publikasi'] ?? $beritum->tanggal_publikasi;
        if ($validated['status'] === 'published' && !$tanggal) {
            $tanggal = now()->toDateString();
        }

        // Regenerate slug hanya jika judul berubah
        $slug = $beritum->slug;
        if ($beritum->judul !== $validated['judul']) {
            $slug = Berita::generateSlug($validated['judul'], $beritum->id);
        }

        $beritum->update([
            'judul'             => $validated['judul'],
            'slug'              => $slug,
            'ringkasan'         => $validated['ringkasan'] ?? null,
            'isi'               => $validated['isi'],
            'kategori'          => $validated['kategori'],
            'status'            => $validated['status'],
            'tanggal_publikasi' => $tanggal,
            'foto'              => $fotoPath,
        ]);

        return redirect()->route('berita.index')
            ->with('success', 'Berita berhasil diperbarui.');
    }

    // ─── Admin: Hapus berita ──────────────────────────────────────────────────

    public function destroy(Berita $beritum)
    {
        if ($beritum->foto) {
            Storage::disk('public')->delete($beritum->foto);
        }
        $beritum->delete();

        return redirect()->route('berita.index')
            ->with('success', 'Berita berhasil dihapus.');
    }

    // ─── Publik: Detail berita ────────────────────────────────────────────────

    public function show(string $slug)
    {
        $berita = Berita::published()->where('slug', $slug)->firstOrFail();

        // Berita terkait (kategori sama, bukan berita ini)
        $terkait = Berita::published()
            ->where('kategori', $berita->kategori)
            ->where('id', '!=', $berita->id)
            ->orderByDesc('tanggal_publikasi')
            ->limit(3)
            ->get();

        return view('berita.show', compact('berita', 'terkait'));
    }
}
