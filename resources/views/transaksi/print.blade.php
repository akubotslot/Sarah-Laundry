<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Nota - {{ $transaksi->kode_transaksi }}</title>
    <style>
        @media print {
            @page {
                margin: 0;
                size: 80mm 200mm;
            }
            body {
                margin: 10mm;
            }
        }
        
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 18px;
            margin: 0;
            font-weight: bold;
        }

        .header p {
            margin: 5px 0;
        }

        .info-item {
            margin-bottom: 5px;
        }

        .info-item span {
            display: inline-block;
            min-width: 100px;
        }

        table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }

        th, td {
            padding: 5px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            font-weight: bold;
            background-color: #f8f8f8;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .layanan-detail {
            font-size: 10px;
            color: #666;
            margin-top: 2px;
        }

        .total-row {
            font-weight: bold;
            border-top: 2px solid #000;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-style: italic;
        }

        .no-print {
            display: none;
        }

        @media screen {
            body {
                width: 80mm;
                margin: 20px auto;
                padding: 15px;
                border: 1px solid #ccc;
            }
            
            .no-print {
                display: block;
                text-align: center;
                margin: 20px 0;
            }
            
            .no-print button {
                padding: 10px 20px;
                background-color: #4F46E5;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                margin: 0 5px;
            }
            
            .no-print button:hover {
                background-color: #4338CA;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ Auth::user()->name }}</h1>
        <p>{{ auth()->user()->alamat_toko }}</p>
        <p>Telp: {{ auth()->user()->nomor_whatsapp }}</p>
    </div>

    <div class="info">
        <div class="info-item">
            <span>No. Nota:</span> {{ $transaksi->kode_transaksi }}
        </div>
        <div class="info-item">
            <span>Tanggal:</span> {{ $transaksi->tanggal_masuk->format('d/m/Y H:i') }}
        </div>
        <div class="info-item">
            <span>Pelanggan:</span> {{ $transaksi->pelanggan->nama }}
        </div>
        <div class="info-item">
            <span>WhatsApp:</span> {{ $transaksi->pelanggan->nomor_whatsapp }}
        </div>
        <div class="info-item">
            <span>Status:</span> {{ $transaksi->status }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="text-align: left;">Layanan</th>
                <th style="text-align: center;">Jumlah</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi->details as $detail)
            <tr>
                <td>
                    {{ $detail->layanan->nama_layanan }}
                    <div class="layanan-detail">
                        Rp {{ number_format($detail->layanan->harga_per_satuan, 0, ',', '.') }}/{{ $detail->layanan->satuan === 'Meter' ? 'Mtr' : $detail->layanan->satuan }}
                    </div>
                </td>
                <td class="text-center">
                    {{ rtrim(rtrim(number_format($detail->jumlah_satuan, 2), '0'), '.') }} {{ $detail->layanan->satuan === 'Meter' ? 'Pcs' : $detail->layanan->satuan }}
                    @if($detail->layanan->satuan === 'Meter' && $detail->panjang_cm && $detail->lebar_cm)
                    <div class="layanan-detail">
                        {{ $detail->panjang_cm }}cm x {{ $detail->lebar_cm }}cm
                    </div>
                    @endif
                </td>
                <td class="text-right">
                    Rp {{ number_format($detail->total_harga, 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="2" class="text-right">Total:</td>
                <td class="text-right">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Terima kasih telah menggunakan jasa kami</p>
        <p>Simpan nota ini sebagai bukti pengambilan</p>
    </div>

    <div class="no-print">
        <button onclick="window.print()">Cetak Nota</button>
        <button onclick="window.close()">Tutup</button>
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html> 