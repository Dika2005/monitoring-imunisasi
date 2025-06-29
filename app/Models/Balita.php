<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\JadwalImunisasi;
use App\Models\RiwayatImunisasi;
use Carbon\Carbon;

class Balita extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'no_telepon',
        'user_id',
        
    ];

    /**
     * Relasi ke User (orang tua)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke JadwalImunisasi
     */
    public function jadwalImunisasi()
    {
        return $this->hasMany(JadwalImunisasi::class);
    }

    /**
     * Relasi ke RiwayatImunisasi
     */
    public function riwayatImunisasi()
    {
        return $this->hasMany(RiwayatImunisasi::class);
    }

    /**
     * Accessor untuk menghitung umur dalam format teks (tahun, bulan, hari)
     */
    public function getUmurFormatAttribute()
    {
        if (!$this->tanggal_lahir) {
            return '-';
        }

        try {
            $lahir = Carbon::parse($this->tanggal_lahir);
            $sekarang = Carbon::now();

            $umur = $lahir->diff($sekarang);

            $umurString = '';
            if ($umur->y > 0) {
                $umurString .= $umur->y . ' tahun ';
            }
            if ($umur->m > 0) {
                $umurString .= $umur->m . ' bulan ';
            }
            if ($umur->y === 0 && $umur->m === 0 && $umur->d > 0) {
                $umurString .= $umur->d . ' hari';
            }
            if (trim($umurString) === '') {
                $umurString = 'Baru lahir';
            }

            return trim($umurString);
        } catch (\Exception $e) {
            return '-';
        }
    }
}
