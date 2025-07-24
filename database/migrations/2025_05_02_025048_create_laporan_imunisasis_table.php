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
            $table->date('tanggal_imunisasi')->nullable(); // diperbolehkan null
            $table->string('status'); // tanpa batasan panjang eksplisit
            $table->timestamps();
            $table->float('suhu_badan')->nullable();
            $table->float('berat_badan')->nullable();
            $table->float('tinggi_badan')->nullable();


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
