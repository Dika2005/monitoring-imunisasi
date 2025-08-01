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
    Schema::table('ketersediaan_vaksins', function (Blueprint $table) {
        $table->string('jenis_imunisasi')->after('nama_vaksin');
    });
}

public function down()
{
    Schema::table('ketersediaan_vaksins', function (Blueprint $table) {
        $table->dropColumn('jenis_imunisasi');
    });
}

};
