<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\TransaksiSheetExport;

class LaporanExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new TransaksiSheetExport(),
            new PengeluaranSheetExport(),
        ];
    }
}

