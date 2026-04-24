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
            $table->string('nik', 16)->nullable()->unique()->after('foto');
            $table->string('no_kk', 16)->nullable()->after('nik');
            $table->string('kelas_saat_masuk', 20)->nullable()->after('no_kk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anak_yatim', function (Blueprint $table) {
            $table->dropUnique(['nik']);
            $table->dropColumn(['nik', 'no_kk', 'kelas_saat_masuk']);
        });
    }
};
