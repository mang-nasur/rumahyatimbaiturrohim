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
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();

            // Anak yatim yang absen
            $table->foreignId('anak_yatim_id')
                  ->constrained('anak_yatim')
                  ->onDelete('cascade');

            // Periode absensi (bulan & tahun)
            $table->unsignedTinyInteger('bulan');  // 1–12
            $table->unsignedSmallInteger('tahun');

            // Siapa yang hadir: 'anak' atau 'ibu'
            $table->enum('hadir_sebagai', ['anak', 'ibu'])->nullable();

            // Status: pending → disetujui / ditolak
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');

            // Catatan dari staff saat approval
            $table->text('catatan_staff')->nullable();

            // Staff yang melakukan approval
            $table->foreignId('approved_by')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');

            $table->timestamp('approved_at')->nullable();

            // Waktu anak/ibu submit absensi
            $table->timestamp('submitted_at')->nullable();

            $table->timestamps();

            // Satu anak hanya bisa absen sekali per bulan per tahun
            $table->unique(['anak_yatim_id', 'bulan', 'tahun'], 'unique_absensi_per_bulan');

            $table->index(['bulan', 'tahun'], 'idx_periode');
            $table->index('status', 'idx_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
