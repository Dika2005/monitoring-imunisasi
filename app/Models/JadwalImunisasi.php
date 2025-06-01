<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalImunisasi extends Model
{
    use HasFactory;

    protected $fillable = ['balita_id', 'tanggal_imunisasi', 'jenis_vaksin'];
    protected $table = 'jadwal_imunisasi';

    // Relasi dengan Balita
    public function balita()
    {
        return $this->belongsTo(Balita::class);
    }
}

