<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class alat extends Model
{
    protected $fillable = [
        'kategori_id',
        'nama_alat',
        'deskripsi',
        'stok',
        'denda_per_hari',
    ];

    public function kategori(){
        return $this->belongsTo(kategori::class);
    }
}
