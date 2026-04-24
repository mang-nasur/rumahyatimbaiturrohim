<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('anak_yatim', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap', 255);
            $table->string('tempat_lahir', 255);
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->text('alamat')->nullable();
            $table->string('nama_ayah', 255)->nullable();
            $table->string('status_ayah', 100)->nullable();
            $table->string('nama_ibu', 255)->nullable();
            $table->string('status_ibu', 100)->nullable();
            $table->string('nomor_telepon_wali', 20)->nullable();
            $table->date('tanggal_masuk');
            $table->string('pendidikan_terakhir', 100)->nullable();
            $table->string('sekolah_saat_ini', 255)->nullable();
            $table->string('foto', 255)->nullable();
            $table->timestamps();

            // Indexes untuk kolom yang sering di-query
            $table->index('nama_lengkap', 'idx_nama');
            $table->index('tanggal_lahir', 'idx_tanggal_lahir');
            $table->index('tanggal_masuk', 'idx_tanggal_masuk');
            $table->index('pendidikan_terakhir', 'idx_pendidikan');
        });

        // Set charset to utf8mb4 (only for MySQL)
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE anak_yatim CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anak_yatim');
    }
};
