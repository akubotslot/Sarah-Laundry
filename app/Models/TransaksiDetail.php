<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaksi_id',
        'layanan_id',
        'jumlah_satuan',
        'panjang_cm',
        'lebar_cm',
        'total_harga'
    ];

    protected $casts = [
        'jumlah_satuan' => 'decimal:1',
        'total_harga' => 'decimal:2'
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }

    public function getJumlahSatuanFormattedAttribute()
    {
        if ($this->layanan->satuan === 'Kg') {
            return number_format($this->jumlah_satuan, 1, ',', '.');
        }
        return number_format($this->jumlah_satuan, 0, ',', '.');
    }
} 