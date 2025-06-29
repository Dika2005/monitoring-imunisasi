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
        Schema::create('riwayat_imunisasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jadwal_imunisasi_id');
            $table->unsignedBigInteger('balita_id');
            $table->string('jenis_vaksin');
            $table->date('tanggal_imunisasi');
            $table->enum('status', ['selesai', 'belum selesai'])->default('belum selesai');
            $table->timestamps();

            $table->foreign('jadwal_imunisasi_id')->references('id')->on('jadwal_imunisasi')->onDelete('cascade');
            $table->foreign('balita_id')->references('id')->on('balitas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_imunisasi');
    }
};
