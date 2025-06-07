<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Balita; // Pastikan ini diimpor
use App\Models\User;   // Pastikan ini diimpor

class JadwalImunisasi extends Model
{
    use HasFactory;

    protected $fillable = ['balita_id', 'tanggal_imunisasi', 'jenis_vaksin'];
    protected $table = 'jadwal_imunisasi';

    /**
     * Dapatkan balita yang memiliki jadwal imunisasi ini.
     */
    public function balita()
    {
        return $this->belongsTo(Balita::class);
    }

    /**
     * Dapatkan user (orang tua/wali) melalui model balita.
     * Ini membuat relasi "has one through".
     */
    public function user()
    {
        return $this->hasOneThrough(
            User::class,   // Model akhir: User
            Balita::class, // Model perantara: Balita
            'id',          // Foreign key di tabel perantara (balitas) yang merujuk ke tabel saat ini (users)
            'id',          // Primary key di tabel target (users)
            'balita_id',   // Foreign key di tabel saat ini (jadwal_imunisasi) yang merujuk ke tabel perantara (balitas)
            'id_orang_tua' // Foreign key di tabel perantara (balitas) yang merujuk ke tabel target (users)
        );
    }
}
