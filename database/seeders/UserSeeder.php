<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'SARAH LAUNDRY',
            'email' => 'sarahlaundry@gmail.com',
            'password' => Hash::make('12345678'),
            'alamat_toko' => 'Perum Taman Persada blok A8/08 Rt 001/009',
            'nomor_whatsapp' => '089699878530',
            'role' => 'admin'
        ]);
    }
}
