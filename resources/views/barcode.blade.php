<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barcode</title>
    <style>
        .barcode {
            width: 1.9cm; /* Ukuran lebar */
            height: 5.0cm; /* Ukuran tinggi */
            overflow: hidden;
            text-align: center; /* Center text */
        }
        .barcode img {
            width: 100%; /* Mengatur lebar gambar agar sesuai dengan ukuran div */
            height: auto; /* Menjaga rasio aspek */
        }
        .barcode-text {
            margin-top: 5px; /* Jarak antara barcode dan teks */
            font-size: 14px; /* Ukuran font */
            font-family: Arial, sans-serif; /* Font yang digunakan */
        }
        .download-button {
            margin-top: 20px; /* Jarak atas untuk tombol download */
        }
    </style>
</head>
<body>
    <div class="barcode">
        <img src="{{ asset($barcode) }}" alt="Barcode">
        <div class="barcode-text">{{ $data }}</div> <!-- Menampilkan data barcode -->
    </div>
    <div class="download-button">
        <a href="{{ url('/download-barcode') }}" class="btn btn-primary">Download Barcode</a>
    </div>
</body>
</html>