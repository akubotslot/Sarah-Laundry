<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nota - {{ $transaksi->kode_transaksi }}</title>
    <style>
        @page {
            size: 105mm 148mm;
            margin: 8mm;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            width: 89mm; /* 105mm - (2 * 8mm margin) */
        }

        .header {
            text-align: center;
            margin-bottom: 8mm;
        }

        .header h1 {
            font-size: 14px;
            font-weight: bold;
            margin: 0 0 2mm 0;
        }

        .header p {
            margin: 1mm 0;
            font-size: 12px;
        }

        .info {
            margin-bottom: 8mm;
        }

        .info-item {
            margin-bottom: 2mm;
        }

        .info-item strong {
            display: inline-block;
            width: 80px;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 8mm 0;
        }

        th, td {
            padding: 2mm;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }

        th {
            background-color: #f8f8f8;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .layanan-detail {
            font-size: 9px;
            color: #666;
            margin-top: 1mm;
        }

        .total-row {
            font-weight: bold;
            border-top: 2px solid #000;
        }

        .footer {
            margin-top: 8mm;
            text-align: center;
            font-style: italic;
            font-size: 10px;
        }

        .footer p {
            margin: 1mm 0;
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
</body>
</html>