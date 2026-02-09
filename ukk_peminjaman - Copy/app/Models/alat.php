<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alat extends Model
{
    protected $table = 'alats'; 

    protected $fillable = [
        'kategori_id',
        'nama_alat',
        'deskripsi',
        'stok',
        'denda_per_hari',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}
