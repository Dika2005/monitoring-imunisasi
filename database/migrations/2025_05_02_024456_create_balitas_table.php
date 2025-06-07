<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void // Mengubah signature menjadi void
    {
        Schema::create('balitas', function (Blueprint $table) {
            $table->id(); // Kolom ID utama

            // Kolom foreign key untuk user (orang tua)
            // Ini akan membuat kolom user_id bertipe BIGINT UNSIGNED NOT NULL
            // dan secara otomatis menambahkan foreign key constraint ke tabel 'users'
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            // onDelete('cascade') berarti jika user dihapus, balita yang terkait juga akan dihapus.
            // Anda bisa mengubahnya menjadi onDelete('set null') jika user_id diizinkan null,
            // atau onDelete('restrict') jika tidak ingin menghapus balita saat user dihapus.

            $table->string('nama');
            $table->date('tanggal_lahir');
            $table->string('jenis_kelamin');
            $table->string('alamat');
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balitas');
    }
};