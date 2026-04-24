<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\AnakYatim;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AbsensiController extends Controller
{
    // ─── Anak / Wali: Submit Absensi ─────────────────────────────────────────

    /**
     * Halaman form absensi bulanan untuk anak yatim.
     * Anak memilih bulan/tahun dan siapa yang hadir.
     */
    public function create(): View
    {
        $user = auth()->user();
        $bulanSekarang = (int) now()->format('n');
        $tahunSekarang = (int) now()->format('Y');

        // Orang tua: langsung ke anak mereka sendiri
        if ($user->isOrangTua()) {
            $anakYatim = $user->anakYatim;

            if (!$anakYatim) {
                abort(403, 'Akun Anda belum terhubung ke data anak yatim. Hubungi pengurus.');
            }

            // Cek apakah sudah absen bulan ini
            $sudahAbsen = \App\Models\Absensi::where('anak_yatim_id', $anakYatim->id)
                ->where('bulan', $bulanSekarang)
                ->where('tahun', $tahunSekarang)
                ->first();

            return view('absensi.create-orang-tua', compact(
                'anakYatim', 'bulanSekarang', 'tahunSekarang', 'sudahAbsen'
            ));
        }

        // Staff/admin: bisa pilih anak mana saja
        $anakYatimList = AnakYatim::aktif()->orderBy('nama_lengkap')->get();

        return view('absensi.create', compact(
            'bulanSekarang',
            'tahunSekarang',
            'anakYatimList'
        ));
    }

    /**
     * Simpan absensi yang disubmit.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();

        // Orang tua: paksa anak_yatim_id ke anak mereka sendiri
        if ($user->isOrangTua()) {
            if (!$user->anak_yatim_id) {
                abort(403, 'Akun Anda belum terhubung ke data anak yatim.');
            }
            $request->merge(['anak_yatim_id' => $user->anak_yatim_id]);
        }

        $request->validate([
            'anak_yatim_id' => 'required|exists:anak_yatim,id',
            'bulan'         => 'required|integer|between:1,12',
            'tahun'         => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'hadir_sebagai' => 'required|in:anak,ibu',
        ], [
            'anak_yatim_id.required' => 'Pilih nama anak yatim.',
            'anak_yatim_id.exists'   => 'Anak yatim tidak ditemukan.',
            'bulan.required'         => 'Bulan wajib dipilih.',
            'tahun.required'         => 'Tahun wajib diisi.',
            'hadir_sebagai.required' => 'Pilih siapa yang hadir.',
            'hadir_sebagai.in'       => 'Pilihan tidak valid.',
        ]);

        // Orang tua hanya boleh absen untuk anak mereka sendiri
        if ($user->isOrangTua() && (int) $request->anak_yatim_id !== (int) $user->anak_yatim_id) {
            abort(403, 'Anda hanya dapat mengisi absensi untuk anak Anda sendiri.');
        }

        // Cek duplikat
        $existing = \App\Models\Absensi::where('anak_yatim_id', $request->anak_yatim_id)
            ->where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->first();

        if ($existing) {
            return back()
                ->withInput()
                ->with('error', 'Absensi untuk bulan ini sudah pernah disubmit. Status: ' . $existing->label_status);
        }

        \App\Models\Absensi::create([
            'anak_yatim_id' => $request->anak_yatim_id,
            'bulan'         => $request->bulan,
            'tahun'         => $request->tahun,
            'hadir_sebagai' => $request->hadir_sebagai,
            'status'        => ($user->isAdmin() || $user->isStaff()) ? 'disetujui' : 'pending',
            'submitted_at'  => now(),
            'approved_by'   => ($user->isAdmin() || $user->isStaff()) ? $user->id : null,
            'approved_at'   => ($user->isAdmin() || $user->isStaff()) ? now() : null,
        ]);

        $pesan = ($user->isAdmin() || $user->isStaff())
            ? 'Absensi berhasil disimpan dan langsung disetujui.'
            : 'Absensi berhasil disubmit. Menunggu persetujuan staff.';

        return redirect()->route('absensi.riwayat', $request->anak_yatim_id)
            ->with('success', $pesan);
    }

    /**
     * Riwayat absensi satu anak yatim.
     */
    public function riwayat(AnakYatim $anakYatim): View
    {
        $user = auth()->user();

        // Orang tua hanya bisa lihat riwayat anak mereka sendiri
        if ($user->isOrangTua() && $user->anak_yatim_id !== $anakYatim->id) {
            abort(403, 'Anda hanya dapat melihat riwayat absensi anak Anda sendiri.');
        }

        $absensiList = $anakYatim->absensi()
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->paginate(12);

        return view('absensi.riwayat', compact('anakYatim', 'absensiList'));
    }

    // ─── Staff / Admin: Approval ──────────────────────────────────────────────

    /**
     * Daftar absensi pending untuk diapprove oleh staff.
     */
    public function indexApproval(Request $request): View
    {
        $bulan = $request->filled('bulan') ? (int) $request->bulan : (int) now()->format('n');
        $tahun = $request->filled('tahun') ? (int) $request->tahun : (int) now()->format('Y');
        $status = $request->get('status', 'pending');

        $query = Absensi::with('anakYatim', 'approvedBy')
            ->where('bulan', $bulan)
            ->where('tahun', $tahun);

        if ($status !== 'semua') {
            $query->where('status', $status);
        }

        $absensiList = $query->orderBy('submitted_at')->paginate(20)->withQueryString();

        // Statistik periode ini
        $totalPending   = Absensi::periode($bulan, $tahun)->pending()->count();
        $totalDisetujui = Absensi::periode($bulan, $tahun)->disetujui()->count();
        $totalDitolak   = Absensi::periode($bulan, $tahun)->where('status', 'ditolak')->count();
        $totalAktif     = AnakYatim::aktif()->count();

        return view('absensi.approval', compact(
            'absensiList',
            'bulan',
            'tahun',
            'status',
            'totalPending',
            'totalDisetujui',
            'totalDitolak',
            'totalAktif'
        ));
    }

    /**
     * Setujui absensi, dengan opsi koreksi siapa yang hadir.
     */
    public function approve(Request $request, Absensi $absensi): RedirectResponse
    {
        if ($absensi->status !== 'pending') {
            return back()->with('error', 'Absensi ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'hadir_sebagai' => 'nullable|in:anak,ibu',
        ]);

        $absensi->update([
            'status'        => 'disetujui',
            'hadir_sebagai' => $request->hadir_sebagai ?? $absensi->hadir_sebagai,
            'approved_by'   => auth()->id(),
            'approved_at'   => now(),
        ]);

        return back()->with('success', "Absensi {$absensi->anakYatim->nama_lengkap} berhasil disetujui.");
    }

    /**
     * Tolak absensi dengan catatan.
     */
    public function reject(Request $request, Absensi $absensi): RedirectResponse
    {
        if ($absensi->status !== 'pending') {
            return back()->with('error', 'Absensi ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'catatan_staff' => 'nullable|string|max:500',
        ]);

        $absensi->update([
            'status'        => 'ditolak',
            'catatan_staff' => $request->catatan_staff,
            'approved_by'   => auth()->id(),
            'approved_at'   => now(),
        ]);

        return back()->with('success', "Absensi {$absensi->anakYatim->nama_lengkap} ditolak.");
    }

    /**
     * Approve semua absensi pending dalam satu periode sekaligus.
     */
    public function approveAll(Request $request): RedirectResponse
    {
        $request->validate([
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer|min:2000',
        ]);

        $count = Absensi::where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->where('status', 'pending')
            ->update([
                'status'      => 'disetujui',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

        return back()->with('success', "{$count} absensi berhasil disetujui sekaligus.");
    }

    // ─── Dashboard: Anak Tidak Hadir ─────────────────────────────────────────

    /**
     * Daftar anak yatim yang tidak hadir ≥ 3 bulan berturut-turut,
     * plus daftar anak yang diwakilkan (hadir_sebagai = 'ibu') beserta hitungannya.
     */
    public function tidakHadir(): View
    {
        $bulanSekarang = (int) now()->format('n');
        $tahunSekarang = (int) now()->format('Y');

        // Ambil semua anak aktif beserta absensi disetujui
        $anakAktif = AnakYatim::aktif()
            ->with(['absensi' => function ($q) {
                $q->where('status', 'disetujui')->orderByDesc('tahun')->orderByDesc('bulan');
            }])
            ->get();

        // Hitung tidak hadir berturut-turut
        $anakAktif = $anakAktif->map(function ($anak) {
            $anak->jumlah_tidak_hadir = $anak->getTidakHadirBerturut();
            return $anak;
        });

        $tidakHadirList = $anakAktif
            ->filter(fn($anak) => $anak->jumlah_tidak_hadir >= 3)
            ->sortByDesc('jumlah_tidak_hadir')
            ->values();

        $tidakHadir1Bulan = $anakAktif->filter(fn($a) => $a->jumlah_tidak_hadir === 1)->count();
        $tidakHadir2Bulan = $anakAktif->filter(fn($a) => $a->jumlah_tidak_hadir === 2)->count();
        $tidakHadir3Plus  = $tidakHadirList->count();

        // Anak yang diwakilkan: hitung total absensi dengan hadir_sebagai = 'ibu'
        $diwakilkanList = AnakYatim::aktif()
            ->withCount([
                'absensi as total_diwakilkan' => fn($q) =>
                    $q->where('status', 'disetujui')->where('hadir_sebagai', 'ibu'),
            ])
            ->having('total_diwakilkan', '>', 0)
            ->orderByDesc('total_diwakilkan')
            ->get();

        return view('absensi.tidak-hadir', compact(
            'tidakHadirList',
            'bulanSekarang',
            'tahunSekarang',
            'tidakHadir1Bulan',
            'tidakHadir2Bulan',
            'tidakHadir3Plus',
            'diwakilkanList'
        ));
    }
}
