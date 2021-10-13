<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class SampleExcelExport implements FromArray, WithHeadings, WithColumnWidths
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function array(): array
    {
        return [
            ['959*********'],
            ['959*********'],
            ['959*********']
        ];
    }

    public function headings(): array
    {
        return [
            'phone_number',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
        ];
    }
}
