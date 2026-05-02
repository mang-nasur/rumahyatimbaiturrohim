<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DonasiController extends Controller
{
    /**
     * Tampilkan form donasi.
     */
    public function create()
    {
        return view('donasi.create');
    }

    /**
     * Proses form donasi dan tampilkan halaman konfirmasi virtual account.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_donatur'  => 'required|string|max:100',
            'nominal'       => 'required|numeric|min:10000',
            'ditujukan'     => 'required|string|max:200',
            'doa'           => 'nullable|string|max:500',
        ], [
            'nama_donatur.required'  => 'Nama donatur wajib diisi.',
            'nama_donatur.max'       => 'Nama donatur maksimal 100 karakter.',
            'nominal.required'       => 'Nominal donasi wajib diisi.',
            'nominal.numeric'        => 'Nominal harus berupa angka.',
            'nominal.min'            => 'Nominal donasi minimal Rp 10.000.',
            'ditujukan.required'     => 'Tujuan donasi wajib diisi.',
            'ditujukan.max'          => 'Tujuan donasi maksimal 200 karakter.',
            'doa.max'                => 'Doa maksimal 500 karakter.',
        ]);

        // Format nilai ditujukan: "kategori|Label Tujuan"
        // Pisahkan kategori (operasional / pembangunan) dari label tampilan
        $ditujukanRaw = $validated['ditujukan'];
        $parts        = explode('|', $ditujukanRaw, 2);
        $kategoriDonasi = $parts[0] ?? 'operasional';   // 'operasional' atau 'pembangunan'
        $labelDitujukan = $parts[1] ?? $ditujukanRaw;   // label bersih untuk ditampilkan

        // Tentukan rekening tujuan berdasarkan kategori
        if ($kategoriDonasi === 'pembangunan') {
            $rekening = [
                'bank'   => 'Bank Syariah Indonesia (BSI)',
                'nomor'  => '7318110233',
                'atas_nama' => 'Pembangunan Baiturrohim',
            ];
        } else {
            $rekening = [
                'bank'   => 'Bank Syariah Indonesia (BSI)',
                'nomor'  => '7294768763',
                'atas_nama' => 'Rumah Yatim Baiturrohim YYS',
            ];
        }

        // Simpan data ke session untuk ditampilkan di halaman konfirmasi
        $request->session()->put('donasi_data', [
            'nama_donatur'    => $validated['nama_donatur'],
            'nominal'         => (int) $validated['nominal'],
            'kategori_donasi' => $kategoriDonasi,
            'ditujukan'       => $labelDitujukan,
            'doa'             => $validated['doa'] ?? null,
            'rekening'        => $rekening,
            'kode_unik'       => rand(100, 999),
            'created_at'      => now()->format('d M Y, H:i'),
        ]);

        return redirect()->route('donasi.konfirmasi');
    }

    /**
     * Tampilkan halaman konfirmasi dengan info virtual account BSI.
     */
    public function konfirmasi(Request $request)
    {
        $data = $request->session()->get('donasi_data');

        if (!$data) {
            return redirect()->route('donasi.create')
                ->with('error', 'Sesi donasi tidak ditemukan. Silakan isi form donasi kembali.');
        }

        // Nominal + kode unik agar transfer mudah diidentifikasi
        $nominalTransfer = $data['nominal'] + $data['kode_unik'];

        return view('donasi.konfirmasi', compact('data', 'nominalTransfer'));
    }

    /**
     * Selesai — bersihkan session dan kembali ke beranda.
     */
    public function selesai(Request $request)
    {
        $request->session()->forget('donasi_data');

        return redirect()->route('home')
            ->with('success', 'Terima kasih atas donasi Anda! Semoga menjadi amal jariyah yang berkah. 🤲');
    }
}
