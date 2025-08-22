<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Obat extends Model
{
    use HasFactory;
    protected $table = 'obats';
    protected $fillable = [
        'nama_obat',
        'satuan',
        'stok',
        'stok_minimum',
        'jenis_obat_id'
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
    public function jenisObat()
    {
        return $this->belongsTo(JenisObat::class, 'jenis_obat_id');
    }
    public function obatRusak()
    {
        return $this->hasMany(ObatRusak::class, 'obat_id');
    }
}
