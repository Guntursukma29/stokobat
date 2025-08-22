<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ObatKeluar extends Model
{
    use HasFactory;

    protected $fillable = [
        // 'pasien_id',
        'tanggal_keluar'
    ];

    // Relasi ke pasien
    // public function pasien()
    // {
    //     return $this->belongsTo(Pasien::class);
    // }

    // Relasi ke detail obat keluar
    public function detail()
    {
        return $this->hasMany(ObatKeluarDetail::class);
    }
}
