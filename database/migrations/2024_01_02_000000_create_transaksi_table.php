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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->enum('jenis', ['penerimaan', 'pengeluaran']);
            $table->string('kategori', 100);
            $table->decimal('jumlah', 15, 2);
            $table->text('keterangan');
            $table->string('bukti_file', 255)->nullable();
            $table->timestamps();

            // Indexes untuk kolom yang sering di-query
            $table->index('tanggal', 'idx_tanggal');
            $table->index('jenis', 'idx_jenis');
            $table->index('kategori', 'idx_kategori');
        });

        // Set charset to utf8mb4 (only for MySQL)
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE transaksi CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
