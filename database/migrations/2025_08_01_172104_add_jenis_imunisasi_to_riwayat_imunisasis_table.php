<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Tambahkan kolom jenis_imunisasi ke tabel riwayat_imunisasis.
     */
    public function up(): void
    {
        Schema::table('riwayat_imunisasi', function (Blueprint $table) {
            $table->string('jenis_imunisasi')->nullable()->after('balita_id');
        });
    }

    /**
     * Hapus kolom jenis_imunisasi jika rollback.
     */
    public function down(): void
    {
        Schema::table('riwayat_imunisasi', function (Blueprint $table) {
            $table->dropColumn('jenis_imunisasi');
        });
    }
};
