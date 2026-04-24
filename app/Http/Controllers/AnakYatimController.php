<?php

namespace App\Http\Controllers;

use App\Models\AnakYatim;
use App\Http\Requests\StoreAnakYatimRequest;
use App\Http\Requests\UpdateAnakYatimRequest;
use App\Exports\LaporanExport;
use App\Services\LaporanService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class AnakYatimController extends Controller
{
    /**
     * Display a listing of the resource with pagination, search, and filters.
     */
    public function index(Request $request): View
    {
        $query = $this->buildFilteredQuery($request);
        $anakYatim = $query->paginate(10)->withQueryString();

        return view('anak-yatim.index', compact('anakYatim'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // Authorization: Only admin and staff can create
        if (!auth()->user()->isAdmin() && !auth()->user()->isStaff()) {
            abort(403, 'Anda tidak memiliki akses untuk menambah data anak yatim.');
        }

        return view('anak-yatim.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAnakYatimRequest $request): RedirectResponse
    {
        // Authorization: Only admin and staff can store
        if (!auth()->user()->isAdmin() && !auth()->user()->isStaff()) {
            abort(403, 'Anda tidak memiliki akses untuk menambah data anak yatim.');
        }

        $data = $request->validated();

        // Handle photo upload if present
        if ($request->hasFile('foto')) {
            $data['foto'] = $this->handlePhotoUpload($request->file('foto'));
        }

        // Jika tanggal_keluar tidak diisi manual, hitung estimasi lulus SMA
        if (empty($data['tanggal_keluar']) && !empty($data['tanggal_masuk'])) {
            // Jika kelas kosong, asumsi Belum Sekolah (hitung dari tanggal lahir)
            $kelas = !empty($data['kelas_saat_masuk']) ? $data['kelas_saat_masuk'] : 'Belum Sekolah';
            $tglLahir = !empty($data['tanggal_lahir'])
                ? \Carbon\Carbon::parse($data['tanggal_lahir'])
                : null;

            $estimasi = \App\Services\EstimasiKeluarCalculator::calculate(
                \Carbon\Carbon::parse($data['tanggal_masuk']),
                $kelas,
                $tglLahir
            );
            $data['tanggal_keluar'] = $estimasi?->format('Y-m-d');

            // Jika kelas kosong, set ke Belum Sekolah
            if (empty($data['kelas_saat_masuk'])) {
                $data['kelas_saat_masuk'] = 'Belum Sekolah';
            }
        }

        // Default is_aktif = true jika tidak diisi
        $data['is_aktif'] = isset($data['is_aktif']) ? (bool) $data['is_aktif'] : true;

        AnakYatim::create($data);

        return redirect()->route('anak-yatim.index')
            ->with('success', 'Data anak yatim berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AnakYatim $anakYatim): View
    {
        $user = auth()->user();

        // Orang tua hanya bisa lihat data anak mereka sendiri
        if ($user->isOrangTua() && $user->anak_yatim_id !== $anakYatim->id) {
            abort(403, 'Anda hanya dapat melihat data anak Anda sendiri.');
        }

        return view('anak-yatim.show', compact('anakYatim'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AnakYatim $anakYatim): View
    {
        $user = auth()->user();

        // Orang tua hanya bisa edit anak mereka sendiri
        if ($user->isOrangTua()) {
            if ($user->anak_yatim_id !== $anakYatim->id) {
                abort(403, 'Anda hanya dapat mengubah data anak Anda sendiri.');
            }
            return view('anak-yatim.edit-orang-tua', compact('anakYatim'));
        }

        // Admin/staff: cek akses normal
        if (!$user->isAdmin() && !$user->isStaff()) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah data anak yatim.');
        }

        return view('anak-yatim.edit', compact('anakYatim'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAnakYatimRequest $request, AnakYatim $anakYatim): RedirectResponse
    {
        $user = auth()->user();

        // Orang tua hanya bisa update anak mereka sendiri
        if ($user->isOrangTua()) {
            if ($user->anak_yatim_id !== $anakYatim->id) {
                abort(403, 'Anda hanya dapat mengubah data anak Anda sendiri.');
            }
        } elseif (!$user->isAdmin() && !$user->isStaff()) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah data anak yatim.');
        }

        $data = $request->validated();

        // Handle new photo upload
        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            $this->deletePhoto($anakYatim->foto);
            
            // Upload new photo
            $data['foto'] = $this->handlePhotoUpload($request->file('foto'));
        }

        // Jika tanggal_keluar tidak diisi manual, hitung ulang dari estimasi
        if (empty($data['tanggal_keluar'])) {
            $kelasMasuk   = $data['kelas_saat_masuk'] ?? $anakYatim->kelas_saat_masuk;
            $tanggalMasuk = isset($data['tanggal_masuk'])
                ? \Carbon\Carbon::parse($data['tanggal_masuk'])
                : $anakYatim->tanggal_masuk;
            $tanggalLahir = isset($data['tanggal_lahir'])
                ? \Carbon\Carbon::parse($data['tanggal_lahir'])
                : $anakYatim->tanggal_lahir;

            if ($kelasMasuk && $tanggalMasuk) {
                $estimasi = \App\Services\EstimasiKeluarCalculator::calculate(
                    $tanggalMasuk, $kelasMasuk, $tanggalLahir
                );
                $data['tanggal_keluar'] = $estimasi?->format('Y-m-d');
            } else {
                $data['tanggal_keluar'] = null;
            }
        }

        // Konversi is_aktif ke boolean
        if (isset($data['is_aktif'])) {
            $data['is_aktif'] = (bool) $data['is_aktif'];
        }

        $anakYatim->update($data);

        // Orang tua kembali ke halaman detail anak mereka
        if (auth()->user()->isOrangTua()) {
            return redirect()->route('anak-yatim.show', $anakYatim)
                ->with('success', 'Data berhasil diperbarui.');
        }

        return redirect()->route('anak-yatim.index')
            ->with('success', 'Data anak yatim berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AnakYatim $anakYatim): RedirectResponse
    {
        // Authorization: Only admin and staff can delete
        if (!auth()->user()->isAdmin() && !auth()->user()->isStaff()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus data anak yatim.');
        }

        // Delete photo if exists
        $this->deletePhoto($anakYatim->foto);

        $anakYatim->delete();

        return redirect()->route('anak-yatim.index')
            ->with('success', 'Data anak yatim berhasil dihapus.');
    }

    /**
     * Export daftar anak yatim ke Excel (dengan filter yang aktif).
     */
    public function exportExcel(Request $request)
    {
        $data = $this->buildFilteredQuery($request)->get();
        $title = 'Data Anak Yatim - ' . now()->format('d/m/Y');
        $filename = 'data-anak-yatim-' . now()->format('Y-m-d-His') . '.xlsx';

        return Excel::download(new LaporanExport($data, $title), $filename);
    }

    /**
     * Export daftar anak yatim ke PDF (dengan filter yang aktif).
     */
    public function exportPdf(Request $request)
    {
        $data = $this->buildFilteredQuery($request)->get();
        $title = 'Data Anak Yatim';
        $tanggalCetak = now()->format('d/m/Y H:i');

        $pdf = Pdf::loadView('laporan.pdf', compact('data', 'title', 'tanggalCetak'));
        $filename = 'data-anak-yatim-' . now()->format('Y-m-d-His') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Build query dengan filter dari request (dipakai index & export).
     */
    private function buildFilteredQuery(Request $request)
    {
        $query = AnakYatim::query();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter kelas saat masuk (exact match — filter lama, tetap didukung)
        if ($request->filled('kelas_saat_masuk')) {
            $query->where('kelas_saat_masuk', $request->kelas_saat_masuk);
        }

        // Filter kelas range (min–max)
        $kelasMin = $request->filled('kelas_min') ? $request->kelas_min : null;
        $kelasMax = $request->filled('kelas_max') ? $request->kelas_max : null;
        if ($kelasMin || $kelasMax) {
            $query->byKelasRange($kelasMin, $kelasMax);
        }

        // Filter usia range (min–max)
        $minAge = $request->filled('min_age') ? (int) $request->min_age : null;
        $maxAge = $request->filled('max_age') ? (int) $request->max_age : null;
        if ($minAge !== null || $maxAge !== null) {
            $effectiveMin = $minAge ?? 0;
            $effectiveMax = $maxAge ?? 100;
            $query->byAgeRange($effectiveMin, $effectiveMax);
        }

        if ($request->filled('status_aktif')) {
            if ($request->status_aktif === 'aktif') {
                $query->aktif();
            } elseif ($request->status_aktif === 'non_aktif') {
                $query->nonAktif();
            }
        }

        return $query->orderBy('nama_lengkap');
    }

    /**
     * Handle photo upload and return the storage path.
     */
    private function handlePhotoUpload(UploadedFile $photo): string
    {
        $filename = time() . '_' . $photo->getClientOriginalName();
        $path = $photo->storeAs('photos', $filename, 'public');
        
        return $path;
    }

    /**
     * Delete photo from storage if it exists.
     */
    private function deletePhoto(?string $photoPath): void
    {
        if ($photoPath && Storage::disk('public')->exists($photoPath)) {
            Storage::disk('public')->delete($photoPath);
        }
    }
}
