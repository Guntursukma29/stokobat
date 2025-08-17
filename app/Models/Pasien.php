<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pasien extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_pasien',
        'nik'
    ];

    // Relasi ke resep obat keluar
    public function resep()
    {
        return $this->hasMany(ObatKeluar::class);
    }
}
