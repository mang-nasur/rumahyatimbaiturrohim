<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berita', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 200);
            $table->string('slug', 220)->unique();
            $table->string('ringkasan', 300)->nullable();
            $table->longText('isi');
            $table->string('foto', 255)->nullable();
            $table->string('kategori', 50)->default('Umum');
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->date('tanggal_publikasi')->nullable();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berita');
    }
};
