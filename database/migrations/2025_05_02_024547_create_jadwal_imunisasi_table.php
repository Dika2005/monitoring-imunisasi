<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('jadwal_imunisasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('balita_id')->constrained()->onDelete('cascade');
            $table->date('tanggal_imunisasi');
            $table->string('jenis_vaksin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Perbaikan: menggunakan nama tabel yang benar
        Schema::dropIfExists('jadwal_imunisasi');
    }
};


