<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Menambah kolom status_akun untuk alur approval pendaftaran orang tua.
     * - pending  : baru daftar, menunggu persetujuan pengurus
     * - aktif    : sudah disetujui, bisa login
     * - ditolak  : ditolak pengurus
     *
     * User internal (admin/staff/bendahara) langsung aktif.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('status_akun', 20)->default('aktif')->after('anak_yatim_id');
            $table->text('catatan_penolakan')->nullable()->after('status_akun');
            $table->foreignId('approved_by')->nullable()->after('catatan_penolakan')
                  ->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable()->after('approved_by');

            $table->index('status_akun', 'idx_status_akun');
        });

        // Semua user yang sudah ada langsung aktif
        DB::table('users')->update(['status_akun' => 'aktif']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_status_akun');
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['status_akun', 'catatan_penolakan', 'approved_by', 'approved_at']);
        });
    }
};
