<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Obat extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_obat',
        'satuan',
        'stok',
        'stok_minimum'
    ];

    // Relasi ke obat masuk
    public function masuk()
    {
        return $this->hasMany(ObatMasuk::class);
    }

    // Relasi ke detail obat keluar
    public function keluarDetail()
    {
        return $this->hasMany(ObatKeluarDetail::class);
    }
}
