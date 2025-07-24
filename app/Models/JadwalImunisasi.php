<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalImunisasi extends Model
{
    use HasFactory;

    protected $table = 'jadwal_imunisasi';

    protected $fillable = [
        'balita_id',
        'tanggal_imunisasi',
        'jenis_vaksin',
    ];

    /**
     * Relasi ke balita
     */
    public function balita()
    {
        return $this->belongsTo(Balita::class, 'balita_id');
    }
    public function riwayat()
{
    return $this->hasOne(RiwayatImunisasi::class, 'jadwal_imunisasi_id');
}
}
