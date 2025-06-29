<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatImunisasi extends Model
{
    use HasFactory;

    protected $table = 'riwayat_imunisasi';

    protected $fillable = [
        'jadwal_imunisasi_id',
        'balita_id',
        'jenis_vaksin',
        'tanggal_imunisasi',
        'status',
    ];

    public function jadwalImunisasi()
    {
        return $this->belongsTo(JadwalImunisasi::class, 'jadwal_imunisasi_id');
    }

    public function balita()
    {
        return $this->belongsTo(Balita::class, 'balita_id');
    }

    // Hapus relasi user() dari sini
}
