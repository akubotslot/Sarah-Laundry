<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Layanan extends Model
{
    use HasFactory;

    protected $table = 'layanans';
    
    protected $fillable = [
        'nama_layanan',
        'satuan',
        'harga_per_satuan'
    ];

    protected $casts = [
        'harga_per_satuan' => 'decimal:2'
    ];

    public function transaksiDetails(): HasMany
    {
        return $this->hasMany(TransaksiDetail::class);
    }
}
