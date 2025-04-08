<?php

namespace App\Exports;

use App\Models\Pengeluaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class PengeluaranSheetExport implements FromCollection, WithMapping, WithHeadings, WithTitle
{
    public function collection()
    {
        return Pengeluaran::all();
    }

    public function map($pengeluaran): array
    {
        return [
            [
                $pengeluaran->nama_pengeluaran,
                $pengeluaran->tanggal->format('d-m-Y H:i'),
                'Rp ' . (fmod($pengeluaran->jumlah, 1) == 0
                    ? number_format($pengeluaran->jumlah, 0, ',', '.')
                    : number_format($pengeluaran->jumlah, 2, ',', '.')),
                $pengeluaran->keterangan ?? '-',
            ]

        ];
    }

    public function headings(): array
    {
        return [
            'Nama Pengeluaran',
            'Tanggal',
            'Jumlah',
            'Keterangan'
        ];
    }
    public function title(): string
    {
        return 'Pengeluaran';
    }
}
