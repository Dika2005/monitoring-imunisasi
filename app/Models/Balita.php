<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balita extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'tanggal_lahir', 'jenis_kelamin', 'alamat'];

    public function laporanImunisasi()
{
    return $this->hasMany(LaporanImunisasi::class);
}
}
