<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KetersediaanVaksin extends Model
{
    use HasFactory;

    protected $table = 'ketersediaan_vaksins';
    protected $fillable = ['nama_vaksin','jenis_imunisasi','stok',];

    public function jadwalImunisasi()
    {
        return $this->hasMany(JadwalImunisasi::class, 'jenis_vaksin', 'nama_vaksin');
    }
}