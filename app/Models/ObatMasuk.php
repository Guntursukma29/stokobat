<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ObatMasuk extends Model
{
    use HasFactory;

    protected $fillable = [
        'obat_id',
        'jumlah',
        'tanggal_masuk'
    ];

    // Relasi ke obat
    public function obat()
    {
        return $this->belongsTo(Obat::class);
    }
}
