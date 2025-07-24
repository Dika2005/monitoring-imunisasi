<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrangTua extends Model
{
    use HasFactory;

    protected $table = 'orang_tuas';

    protected $fillable = ['nama', 'email', 'no_telepon', 'password'];

    protected $hidden = ['password'];

    /**
     * Relasi ke Balita
     * Seorang orang tua bisa punya banyak balita
     */
    public function balitas()
    {
        return $this->hasMany(Balita::class, 'orangtua_id');
    }

    public function getRouteKeyName()
    {
        return 'id';
    }
}
