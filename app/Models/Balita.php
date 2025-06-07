<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\JadwalImunisasi;
use App\Models\LaporanImunisasi;

class Balita extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'user_id'
    ];

    /**
     * Relasi Balita ke User (orang tua/wali).
     * Gunakan foreign key 'user_id' di tabel balitas.
     */
    public function user()
    {
       return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi Balita ke jadwal imunisasi.
     */
    public function jadwalImunisasi()
    {
        return $this->hasMany(JadwalImunisasi::class);
    }

    /**
     * Relasi Balita ke laporan imunisasi.
     */
    public function laporanImunisasi()
    {
        return $this->hasMany(LaporanImunisasi::class);
    }
}
