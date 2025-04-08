<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran'; // Tabel di DB
    protected $fillable = ['nama_pengeluaran', 'jumlah',  'tanggal', 'keterangan'];
    protected $casts = [
        'tanggal' => 'datetime',
    ];
}
