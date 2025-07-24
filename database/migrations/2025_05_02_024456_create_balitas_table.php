<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('balitas', function (Blueprint $table) {
            $table->id(); // Kolom ID utama

            $table->string('nama'); // Nama balita
            $table->date('tanggal_lahir'); // Tanggal lahir balita
            $table->string('jenis_kelamin'); // Jenis kelamin balita

            // Ganti dari nama_orang_tua menjadi foreign key orangtua_id
            $table->unsignedBigInteger('orangtua_id'); // Relasi ke tabel orangtuas
            $table->foreign('orangtua_id')->references('id')->on('orangtuas')->onDelete('cascade');

            // Tambahan kolom pertumbuhan
            $table->float('suhu_badan')->nullable(); // dalam °C
            $table->float('berat_badan')->nullable(); // dalam kg
            $table->float('tinggi_badan')->nullable(); // dalam cm

            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Batalkan migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('balitas');
    }
};
