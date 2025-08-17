<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ObatKeluarDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'obat_keluar_id',
        'obat_id',
        'jumlah'
    ];

    // Relasi ke obat keluar (resep)
    public function obatKeluar()
    {
        return $this->belongsTo(ObatKeluar::class);
    }

    // Relasi ke obat
    public function obat()
    {
        return $this->belongsTo(Obat::class);
    }
}
