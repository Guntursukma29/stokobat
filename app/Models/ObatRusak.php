<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ObatRusak extends Model
{
    protected $table = 'obat_rusak';

    protected $fillable = [
        'obat_id',
        'jumlah',
        'tanggal',
        'keterangan',
    ];

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'obat_id');
    }
}
