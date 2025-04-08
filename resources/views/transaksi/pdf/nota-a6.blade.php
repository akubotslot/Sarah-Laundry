<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nota Transaksi</title>
    <style>
        /* Reset default margin dan padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Pengaturan dasar */
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            background: white;
            width: 95mm; /* Mengurangi width untuk margin */
            margin: 5mm;
        }

        .content-wrapper {
            position: relative;
            min-height: 130mm; /* Mengurangi height untuk margin */
        }

        .header {
            text-align: center;
            margin-bottom: 5mm;
        }

        .header h1 {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 2mm;
        }

        .header p {
            font-size: 12px;
            margin-bottom: 1mm;
        }

        .info {
            margin-bottom: 5mm;
        }

        .info-item {
            margin-bottom: 2mm;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5mm;
            table-layout: fixed;
        }

        th, td {
            padding: 2px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        th {
            font-weight: bold;
            background-color: #f8f8f8;
        }

        .text-right {
            text-align: right;
        }

        th:nth-child(1), td:nth-child(1) { width: 35%; }
        th:nth-child(2), td:nth-child(2) { width: 25%; }
        th:nth-child(3), td:nth-child(3) { width: 20%; }
        th:nth-child(4), td:nth-child(4) { width: 20%; }

        .total {
            text-align: right;
            margin: 3mm 0;
            font-weight: bold;
            font-size: 13px;
            padding-right: 2mm;
        }

        .footer {
            position: absolute;
            bottom: 5mm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            background: white;
        }

        .footer p {
            margin-bottom: 1mm;
        }

        /* Khusus untuk tampilan print */
        @page {
            size: 105mm 148mm;
            margin: 0;
        }

        @media print {
            body {
                width: 95mm;
                margin: 5mm;
            }
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
            <p>{{ auth()->user()->alamat_toko }}</p>
            <p>Telp: {{ auth()->user()->nomor_whatsapp }}</p>
        </div>

        <div class="info">
            <div class="info-item">
                <strong>Kode:</strong> {{ $transaksi->kode_transaksi }}
            </div>
            <div class="info-item">
                <strong>Tanggal:</strong> {{ $transaksi->tanggal_masuk->format('d/m/Y H:i') }}
            </div>
            <div class="info-item">
                <strong>Pelanggan:</strong> {{ $transaksi->pelanggan->nama }}
            </div>
            <div class="info-item">
                <strong>WhatsApp:</strong> {{ $transaksi->pelanggan->nomor_whatsapp }}
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Layanan</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        {{ $transaksi->layanan->nama_layanan }}
                    </td>
                    <td>{{ number_format($transaksi->layanan->harga_per_satuan, 0, ',', '.') }}</td>
                    <td class="text-left">
                        @if($transaksi->layanan->satuan === 'Meter')
                            {{ rtrim(rtrim(number_format($transaksi->jumlah_satuan, 2), '0'), '.') }} Pcs
                            <br>
                            <small>Uk: {{ $transaksi->panjang_cm }}cm x {{ $transaksi->lebar_cm }}cm</small>
                        @else
                            {{ rtrim(rtrim(number_format($transaksi->jumlah_satuan, 2), '0'), '.') }} {{ $transaksi->layanan->satuan }}
                        @endif
                    </td>
                    <td>{{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="total">
            Total: Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
        </div>

        <div class="footer">
            <p>Terima kasih telah menggunakan jasa kami</p>
            <p>Simpan nota ini sebagai bukti pengambilan</p>
        </div>
    </div>
</body>
</html> 