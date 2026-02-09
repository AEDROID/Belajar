<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $fillable = [
        'user_id',
        'alat_id',
        'jumlah',
        'petugas_id',
        'tanggal_peminjaman',
        'tanggal_pengembalian_rencana',
        'tanggal_pengembalian_aktual',
        'status',
        'status_denda',
        'denda',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function alat()
    {
        return $this->belongsTo(Alat::class);
    }
}
