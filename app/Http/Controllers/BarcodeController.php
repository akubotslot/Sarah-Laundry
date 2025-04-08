<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Milon\Barcode\DNS1D;

class BarcodeController extends Controller
{
    public function generateBarcode($kode_transaksi)
    {
        // Data untuk barcode
        $data = $kode_transaksi; // Menggunakan kode transaksi sebagai data barcode

        // Membuat barcode
        $barcode = new DNS1D();
        $barcode->setStorPath(public_path('barcodes/')); // Menyimpan barcode di folder public/barcodes

        // Ukuran untuk barcode
        $height = 72; // Tinggi dalam piksel

        // Menghasilkan barcode PNG
        $barcodeImage = $barcode->getBarcodePNG($data, 'C128B', 1, $height); // Menggunakan C128B untuk kombinasi huruf dan angka

        // Mengonversi PNG ke JPG
        $image = imagecreatefromstring(base64_decode($barcodeImage));
        $jpgFilePath = public_path('barcodes/barcode_' . $kode_transaksi . '.jpg'); // Path untuk file JPG

        // Menambahkan teks ke gambar
        $textColor = imagecolorallocate($image, 0, 0, 0); // Warna teks (hitam)
        $fontSize = 12; // Ukuran font
        $fontPath = public_path('fonts/Roboto-Regular.ttf'); // Path ke font TTF yang diunduh

        // Menentukan posisi teks
        $textBoundingBox = imagettfbbox($fontSize, 0, $fontPath, $data);
        $textWidth = $textBoundingBox[2] - $textBoundingBox[0];
        $textX = (imagesx($image) - $textWidth) / 2; // Center text horizontally

        // Menentukan posisi vertikal teks di bawah gambar
        $textY = imagesy($image) + 15; // Geser teks ke bawah (10 piksel di bawah gambar)

        // Membuat gambar baru untuk menampung barcode dan teks
        $finalImageHeight = imagesy($image) + $fontSize + 5; // Tinggi total gambar (kurangi dari 25 menjadi 15)
        $finalImageWidth = imagesx($image); // Lebar gambar sama dengan lebar barcode
        $finalImage = imagecreatetruecolor($finalImageWidth, $finalImageHeight); // Gambar baru

        // Mengisi latar belakang dengan putih
        $white = imagecolorallocate($finalImage, 255, 255, 255);
        imagefill($finalImage, 0, 0, $white);

        // Menyalin barcode ke gambar baru
        imagecopy($finalImage, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));

        // Menambahkan teks ke gambar baru 
        imagettftext($finalImage, $fontSize, 0, $textX, $textY, $textColor, $fontPath, $data);

        // Simpan sebagai JPG dengan kualitas 100
        imagejpeg($finalImage, $jpgFilePath, 100);
        imagedestroy($image); // Hapus gambar barcode dari memori
        imagedestroy($finalImage); // Hapus gambar final dari memori

        // Mengembalikan file untuk diunduh
        return response()->download($jpgFilePath)->deleteFileAfterSend(true);
    }
     
}
