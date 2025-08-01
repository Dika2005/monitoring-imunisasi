<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ketersediaan_vaksins', function (Blueprint $table) {
            $table->string('jenis_imunisasi')->after('nama_vaksin')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('ketersediaan_vaksins', function (Blueprint $table) {
            $table->dropColumn('jenis_imunisasi');
        });
    }
};
