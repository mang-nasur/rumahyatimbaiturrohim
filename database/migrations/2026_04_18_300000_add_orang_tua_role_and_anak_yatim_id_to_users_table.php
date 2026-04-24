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
     * Menambah role 'orang_tua' ke kolom role dan kolom anak_yatim_id
     * sebagai relasi ke anak yatim yang diwakili.
     */
    public function up(): void
    {
        // Ubah kolom role menjadi string agar bisa menampung nilai baru
        // (ALTER COLUMN ENUM tidak bisa langsung di semua driver)
        Schema::table('users', function (Blueprint $table) {
            // Ganti enum dengan string untuk fleksibilitas
            $table->string('role', 20)->default('staff')->change();

            // FK ke anak_yatim — nullable karena hanya orang tua yang punya
            $table->foreignId('anak_yatim_id')
                  ->nullable()
                  ->after('role')
                  ->constrained('anak_yatim')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['anak_yatim_id']);
            $table->dropColumn('anak_yatim_id');
            $table->enum('role', ['admin', 'bendahara', 'staff'])->default('staff')->change();
        });
    }
};
