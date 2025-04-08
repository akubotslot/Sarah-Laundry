<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksis';
    
    protected $fillable = [
        'kode_transaksi',
        'pelanggan_id',
        'total_harga',
        'status',
        'tanggal_masuk',
        'tanggal_selesai',
        'tanggal_diambil'
    ];

    protected $casts = [
        'tanggal_masuk' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'tanggal_diambil' => 'datetime'
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
        
    }

    public function details()
    {
        return $this->hasMany(TransaksiDetail::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaksi) {
            if (empty($transaksi->kode_transaksi)) {
                $transaksi->kode_transaksi = 'SL-' . date('Ymd') . '-' . str_pad(static::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
