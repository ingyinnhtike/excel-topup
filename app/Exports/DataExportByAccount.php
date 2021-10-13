<?php

namespace App\Exports;

use App\BillRequest;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\FromArray;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;

class DataExportByAccount implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, WithColumnWidths, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {

        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Phone Number',
            'Merchant',
            'Package Name',
            'Package Volume',
            'Batch',
            'Status',
            'Created At',
            'Updated At'
        ];
    }

    public function title(): string
    {
        return "data export";
    }

    public function map($project): array
    {
        return [
            $project->phone_number,
            $project->customer_name,
            $project->package_name,
            $project->volume,
            $project->name,
            $project->status,
            date('Y-m-d H:i:s', strtotime($project->created_at)),
            date('Y-m-d H:i:s', strtotime($project->updated_at))
        ];
    }

    // public function startCell(): string
    // {
    //     return 'A3';
    // }


    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'H' => NumberFormat::FORMAT_DATE_YYYYMMDD,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 15,
            'C' => 30,
            'D' => 15,
            'E' => 30,
            'F' => 10,
            'G' => 20,
            'H' => 20,
        ];
    }
}
