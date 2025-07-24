<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Balita extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'tanggal_lahir',
        'jenis_kelamin',
        'no_telepon',
        'orangtua_id',
        'suhu_badan',
        'berat_badan',
        'tinggi_badan',
    ];

    /**
     * Konversi otomatis kolom ke tipe data yang tepat
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
        'suhu_badan' => 'float',
        'berat_badan' => 'float',
        'tinggi_badan' => 'float',
    ];

    /**
     * Relasi ke Jadwal Imunisasi
     */
    public function jadwalImunisasi()
    {
        return $this->hasMany(JadwalImunisasi::class);
    }

    /**
     * Relasi ke Orang Tua
     */
    public function orangtua()
    {
        return $this->belongsTo(Orangtua::class, 'orangtua_id');
    }

    /**
     * Relasi ke Riwayat Imunisasi
     */
    public function riwayatImunisasi()
    {
        return $this->hasMany(RiwayatImunisasi::class);
    }

    /**
     * Accessor untuk mendapatkan umur dalam format teks
     * Contoh output: "2 tahun 3 bulan", "5 bulan", "10 hari", "Baru lahir"
     */
    public function getUmurFormatAttribute()
{
    if (!$this->tanggal_lahir) {
        return '-';
    }

    try {
        $lahir = \Carbon\Carbon::parse($this->tanggal_lahir);
        $sekarang = \Carbon\Carbon::now(); // atau pakai tanggal imunisasi jika mau

        $umur = $lahir->diff($sekarang);

        if ($umur->y > 0) {
            return $umur->y . ' tahun ' . ($umur->m > 0 ? $umur->m . ' bulan' : '');
        }

        if ($umur->m > 0) {
            return $umur->m . ' bulan';
        }

        if ($umur->d > 0) {
            return $umur->d . ' hari';
        }

        return 'Baru lahir';
    } catch (\Exception $e) {
        return '-';
    }
}

    public function getRouteKeyName()
{
    return 'id';
}


}
