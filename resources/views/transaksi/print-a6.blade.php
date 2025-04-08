<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nota - {{ $transaksi->kode_transaksi }}</title>
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
            background: #f0f0f0;
        }

        .no-print {
            text-align: center;
            margin-bottom: 5mm;
            padding: 5mm;
        }

        .no-print button {
            padding: 2mm 4mm;
            margin: 0 2mm;
            background: #4F46E5;
            color: white;
            border: none;
            border-radius: 2mm;
            cursor: pointer;
            font-size: 12px;
        }

        .no-print button:hover {
            background: #4338CA;
        }

        .content-wrapper {
            background: white;
            width: 148mm;
            min-height: 105mm;
            margin: 0 auto;
            padding: 8mm;
            position: relative;
        }

        .header {
            text-align: center;
            margin-bottom: 8mm;
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
            margin-bottom: 8mm;
        }

        .info-item {
            margin-bottom: 2mm;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8mm;
        }

        th, td {
            padding: 2mm;
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
            margin-top: 1mm;
        }

        .total-row {
            font-weight: bold;
            border-top: 2px solid #000;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            margin-top: 8mm;
            font-style: italic;
        }

        .footer p {
            margin-bottom: 1mm;
        }

        /* Khusus untuk tampilan di layar/preview */
        @media screen {
            html {
                min-height: 100%;
                padding: 5mm;
            }
            
            .content-wrapper {
                box-shadow: 0 0 5mm rgba(0,0,0,0.1);
                margin: 5mm auto;
            }
        }

        /* Khusus untuk tampilan print */
        @media print {
            @page {
                size: A6 portrait;
                margin: 0;
            }

            body {
                background: white;
            }

            .content-wrapper {
                width: 105mm;
                height: 148mm;
                margin: 0;
                padding: 8mm;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()">Cetak Nota</button>
        <button onclick="window.close()">Tutup</button>
    </div>

    <div class="content-wrapper">
        <div class="header">
            <h1> {{ Auth::user()->name }} </h1>
            <p>{{ auth()->user()->alamat_toko }}</p>
            <p>Telp: {{ auth()->user()->nomor_whatsapp }}</p>
        </div>

        <div class="info">
            <div class="info-item">
                <strong>No. Nota:</strong> {{ $transaksi->kode_transaksi }}
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
            <div class="info-item">
                <strong>Status:</strong> {{ $transaksi->status }}
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
                            Rp {{ number_format($detail->layanan->harga_per_satuan, 0, ',', '.') }}/{{ $detail->layanan->satuan === 'Meter' ? 'Meter' : $detail->layanan->satuan }}
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