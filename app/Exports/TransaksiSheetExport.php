<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Collection;

class TransaksiSheetExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection(): Collection
    {
        $transaksis = Transaksi::with(['pelanggan', 'details.layanan'])->get();

        $rows = [];

        foreach ($transaksis as $transaksi) {
            foreach ($transaksi->details as $detail) {
                $rows[] = [
                    'kode_transaksi' => $transaksi->kode_transaksi,
                    'nama_pelanggan' => $transaksi->pelanggan->nama ?? '-',
                    'tanggal_masuk'  => $transaksi->tanggal_masuk ? $transaksi->tanggal_masuk->format('d-m-Y H:i') : '-',
                    'status'         => $transaksi->status,
                    'nama_layanan'   => $detail->layanan->nama_layanan ?? '-',
                    'harga layanan' => 'Rp ' . (fmod($detail->layanan->harga_per_satuan, 1) == 0
                        ? number_format($detail->layanan->harga_per_satuan, 0, ',', '.')
                        : number_format($detail->layanan->harga_per_satuan, 2, ',', '.')),
                    'jumlah_satuan' => $this->formatJumlahSatuan($detail),
                    'ukuran_cm'      => $this->formatUkuran($detail->panjang_cm, $detail->lebar_cm),
                    'total_harga' => 'Rp ' . (fmod($detail->total_harga, 1) == 0
                        ? number_format($detail->total_harga, 0, ',', '.')
                        : number_format($detail->total_harga, 2, ',', '.')),
                ];
            }
        }

        return collect($rows);
    }

    public function headings(): array
    {
        return [
            'Kode Transaksi',
            'Nama Pelanggan',
            'Tanggal Masuk',
            'Status',
            'Nama Layanan',
            'Harga Layanan',
            'Jumlah Satuan',
            'Ukuran (cm)',
            'Total Harga',
        ];
    }

    public function title(): string
    {
        return 'Transaksi';
    }

    private function formatUkuran($panjang, $lebar): string
    {
        if ($panjang && $lebar) {
            return "{$panjang} x {$lebar}";
        }

        return '-';
    }
    private function formatJumlahSatuan($detail)
    {
        $satuan = $this->getSatuanLabel($detail);
        $jumlah = $detail->jumlah_satuan;

        // Cek apakah desimalnya .0
        if (fmod($jumlah, 1.0) === 0.0) {
            $jumlah = number_format($jumlah, 0, ',', '.');
        } else {
            $jumlah = number_format($jumlah, 1, ',', '.');
        }

        return "{$jumlah} {$satuan}";
    }

    private function getSatuanLabel($detail)
    {
        $satuan = strtolower($detail->layanan->satuan ?? '');

        if ($satuan === 'kg') {
            return 'Kg';
        } elseif ($satuan === 'pcs' || $satuan === 'meter') {
            return 'Pcs';
        }

        return ''; // fallback kalau satuan tidak dikenal
    }
}
