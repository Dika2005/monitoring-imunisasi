<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanImunisasi extends Model
{
    use HasFactory;

    protected $table = 'laporan_imunisasi'; // Nama tabel di database

    protected $fillable = [
        'jadwal_imunisasi_id',
        'balita_id',
        'jenis_vaksin',
        'tanggal_imunisasi',
        'status',
    ];

    // Relasi dengan model JadwalImunisasi
    public function jadwalImunisasi()
    {
        return $this->belongsTo(JadwalImunisasi::class, 'jadwal_imunisasi_id');
    }

    // Relasi dengan model Balita
    public function balita()
    {
        return $this->belongsTo(Balita::class, 'balita_id');
    }
}

