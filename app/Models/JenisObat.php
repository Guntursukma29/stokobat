<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisObat extends Model
{
    protected $table = 'jenis_obat';
    protected $fillable = ['nama'];

    public function obat()
    {
        return $this->hasMany(Obat::class, 'jenis_obat_id');
    }
}
