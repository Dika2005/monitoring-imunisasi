<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrangTua extends Model
{
    use HasFactory;

    protected $table = 'orang_tuas';

    protected $fillable = ['nama', 'email', 'no_telepon', 'password', 'user_id'];

    protected $hidden = ['password'];

    /**
     * Relasi ke Balita
     * Seorang orang tua bisa punya banyak balita
     */
    public function balitas()
{
    return $this->hasMany(Balita::class, 'orangtua_id'); // juga disesuaikan
}



    public function getRouteKeyName()
    {
        return 'id';
    }

    public function user()
{
    return $this->belongsTo(User::class);
}

}
