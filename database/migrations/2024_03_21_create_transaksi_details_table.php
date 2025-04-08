<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transaksi_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->constrained('transaksis')->onDelete('cascade');
            $table->foreignId('layanan_id')->constrained('layanans');
            $table->decimal('jumlah_satuan', 10, 2);
            $table->integer('panjang_cm')->nullable();
            $table->integer('lebar_cm')->nullable();
            $table->decimal('total_harga', 12, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaksi_details');
    }
}; 