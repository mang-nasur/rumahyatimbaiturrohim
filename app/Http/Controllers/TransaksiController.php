<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Services\TransaksiService;
use App\Http\Requests\StoreTransaksiRequest;
use App\Http\Requests\UpdateTransaksiRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TransaksiController extends Controller
{
    /**
     * The transaksi service instance.
     *
     * @var TransaksiService
     */
    protected TransaksiService $transaksiService;

    /**
     * Create a new controller instance.
     *
     * @param TransaksiService $transaksiService
     */
    public function __construct(TransaksiService $transaksiService)
    {
        $this->transaksiService = $transaksiService;
    }

    /**
     * Display a listing of transactions with filters and pagination.
     */
    public function index(Request $request): View
    {
        $filters = [
            'tanggal_dari' => $request->input('tanggal_dari'),
            'tanggal_sampai' => $request->input('tanggal_sampai'),
            'jenis' => $request->input('jenis'),
            'kategori' => $request->input('kategori'),
            'search' => $request->input('search'),
        ];

        $query = $this->transaksiService->getTransaksiWithFilters($filters);
        $transaksi = $query->paginate(15)->withQueryString();

        // Get kategori options based on jenis filter
        $kategoriOptions = $this->getKategoriOptions($filters['jenis']);

        return view('transaksi.index', compact('transaksi', 'kategoriOptions'));
    }

    /**
     * Show the form for creating a new transaction.
     */
    public function create(): View
    {
        // Authorization: Only admin and bendahara can create
        if (!auth()->user()->isAdmin() && !auth()->user()->isBendahara()) {
            abort(403, 'Anda tidak memiliki akses untuk menambah transaksi.');
        }

        $kategoriPenerimaan = Transaksi::KATEGORI_PENERIMAAN;
        $kategoriPengeluaran = Transaksi::KATEGORI_PENGELUARAN;

        return view('transaksi.create', compact('kategoriPenerimaan', 'kategoriPengeluaran'));
    }

    /**
     * Store a newly created transaction in storage.
     */
    public function store(StoreTransaksiRequest $request): RedirectResponse
    {
        // Authorization: Only admin and bendahara can store
        if (!auth()->user()->isAdmin() && !auth()->user()->isBendahara()) {
            abort(403, 'Anda tidak memiliki akses untuk menambah transaksi.');
        }

        $this->transaksiService->createTransaksi($request->validated());

        return redirect()->route('transaksi.index')
            ->with('success', 'Transaksi berhasil ditambahkan.');
    }

    /**
     * Display the specified transaction.
     */
    public function show(Transaksi $transaksi): View
    {
        return view('transaksi.show', compact('transaksi'));
    }

    /**
     * Show the form for editing the specified transaction.
     */
    public function edit(Transaksi $transaksi): View
    {
        // Authorization: Only admin and bendahara can edit
        if (!auth()->user()->isAdmin() && !auth()->user()->isBendahara()) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah transaksi.');
        }

        $kategoriPenerimaan = Transaksi::KATEGORI_PENERIMAAN;
        $kategoriPengeluaran = Transaksi::KATEGORI_PENGELUARAN;

        return view('transaksi.edit', compact('transaksi', 'kategoriPenerimaan', 'kategoriPengeluaran'));
    }

    /**
     * Update the specified transaction in storage.
     */
    public function update(UpdateTransaksiRequest $request, Transaksi $transaksi): RedirectResponse
    {
        // Authorization: Only admin and bendahara can update
        if (!auth()->user()->isAdmin() && !auth()->user()->isBendahara()) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah transaksi.');
        }

        $this->transaksiService->updateTransaksi($transaksi, $request->validated());

        return redirect()->route('transaksi.index')
            ->with('success', 'Transaksi berhasil diperbarui.');
    }

    /**
     * Remove the specified transaction from storage.
     */
    public function destroy(Transaksi $transaksi): RedirectResponse
    {
        // Authorization: Only admin and bendahara can delete
        if (!auth()->user()->isAdmin() && !auth()->user()->isBendahara()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus transaksi.');
        }

        $this->transaksiService->deleteTransaksi($transaksi);

        return redirect()->route('transaksi.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }

    /**
     * Get kategori options based on jenis.
     *
     * @param string|null $jenis
     * @return array
     */
    private function getKategoriOptions(?string $jenis): array
    {
        if ($jenis === Transaksi::JENIS_PENERIMAAN) {
            return Transaksi::KATEGORI_PENERIMAAN;
        } elseif ($jenis === Transaksi::JENIS_PENGELUARAN) {
            return Transaksi::KATEGORI_PENGELUARAN;
        }

        return array_merge(Transaksi::KATEGORI_PENERIMAAN, Transaksi::KATEGORI_PENGELUARAN);
    }
}
