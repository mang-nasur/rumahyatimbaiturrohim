<?php

namespace App\Services;

use Carbon\Carbon;

class EstimasiKeluarCalculator
{
    /**
     * Pemetaan kelas ke nomor tingkat (1-12).
     */
    public const KELAS_MAP = [
        'Kelas 1 SD'  => 1,
        'Kelas 2 SD'  => 2,
        'Kelas 3 SD'  => 3,
        'Kelas 4 SD'  => 4,
        'Kelas 5 SD'  => 5,
        'Kelas 6 SD'  => 6,
        'Kelas 1 SMP' => 7,
        'Kelas 2 SMP' => 8,
        'Kelas 3 SMP' => 9,
        'Kelas 1 SMA' => 10,
        'Kelas 2 SMA' => 11,
        'Kelas 3 SMA' => 12,
    ];

    public static function getNomorTingkat(string $kelas): ?int
    {
        return self::KELAS_MAP[$kelas] ?? null;
    }

    /**
     * Hitung estimasi keluar (lulus SMA).
     *
     * Kasus 1 — kelas diketahui (Kelas 1 SD s.d. Kelas 3 SMA):
     *   sisaTingkat = 12 - nomorTingkat
     *   Semester ganjil (Jul–Des): tahunKeluar = tahunMasuk + sisaTingkat + 1
     *   Semester genap (Jan–Jun):  tahunKeluar = tahunMasuk + sisaTingkat
     *
     * Kasus 2 — "Belum Sekolah":
     *   Asumsi masuk kelas 1 SD saat usia 7 tahun.
     *   tahunMasukSD = tahunLahir + 7  (disesuaikan semester)
     *   Lulus SMA = tahunMasukSD + 12 tahun
     *   Butuh tanggal_lahir untuk menghitung ini.
     *
     * @param Carbon|null $tanggalMasuk   Tanggal masuk ke yayasan
     * @param string|null $kelasSaatMasuk Kelas saat masuk, atau "Belum Sekolah"
     * @param Carbon|null $tanggalLahir   Diperlukan jika kelasSaatMasuk = "Belum Sekolah"
     */
    public static function calculate(
        ?Carbon $tanggalMasuk,
        ?string $kelasSaatMasuk,
        ?Carbon $tanggalLahir = null
    ): ?Carbon {
        if ($tanggalMasuk === null || $kelasSaatMasuk === null) {
            return null;
        }

        // ── Kasus: Belum Sekolah ──────────────────────────────────────────────
        if ($kelasSaatMasuk === 'Belum Sekolah') {
            if ($tanggalLahir === null) {
                return null;
            }

            // Tahun anak berusia 7 (estimasi masuk kelas 1 SD)
            $tahunLahir   = (int) $tanggalLahir->format('Y');
            $bulanLahir   = (int) $tanggalLahir->format('n');
            $tahunMasukSD = $tahunLahir + 7;

            // Jika lahir setelah Juni, masuk SD tahun berikutnya
            // (tahun ajaran baru Juli, anak harus sudah 7 tahun sebelum Juli)
            if ($bulanLahir > 6) {
                $tahunMasukSD++;
            }

            // Lulus SMA = masuk SD + 12 tahun, kelulusan bulan Juni
            return Carbon::create($tahunMasukSD + 12, 6, 30);
        }

        // ── Kasus: Kelas diketahui ────────────────────────────────────────────
        $nomorTingkat = self::getNomorTingkat($kelasSaatMasuk);

        if ($nomorTingkat === null) {
            return null;
        }

        $sisaTingkat = 12 - $nomorTingkat;
        $tahunMasuk  = (int) $tanggalMasuk->format('Y');
        $bulanMasuk  = (int) $tanggalMasuk->format('n');

        // Semester ganjil: Juli (7) – Desember (12)
        if ($bulanMasuk >= 7 && $bulanMasuk <= 12) {
            $tahunKeluar = $tahunMasuk + $sisaTingkat + 1;
        } else {
            $tahunKeluar = $tahunMasuk + $sisaTingkat;
        }

        return Carbon::create($tahunKeluar, 6, 30);
    }
}
