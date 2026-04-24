<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('anak_yatim', function (Blueprint $table) {
            // Tanggal keluar: dihitung otomatis dari estimasi lulus SMA,
            // bisa juga diisi manual jika anak keluar lebih awal.
            $table->date('tanggal_keluar')->nullable()->after('kelas_saat_masuk');

            // Status aktif: true = masih terdaftar, false = sudah keluar/lulus
            $table->boolean('is_aktif')->default(true)->after('tanggal_keluar');

            $table->index('is_aktif', 'idx_is_aktif');
            $table->index('tanggal_keluar', 'idx_tanggal_keluar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anak_yatim', function (Blueprint $table) {
            $table->dropIndex('idx_is_aktif');
            $table->dropIndex('idx_tanggal_keluar');
            $table->dropColumn(['tanggal_keluar', 'is_aktif']);
        });
    }
};
