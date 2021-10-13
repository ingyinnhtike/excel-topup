<?php

namespace App\Exports;

use App\BillRequest;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class BillRequestExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, WithTitle, WithColumnWidths
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
            'Service Name',
            'Batch',
            'Status',
            'Created At',
            'Updated At'
        ];
    }

    public function title(): string
    {
        return "bill export";
    }

    public function map($project): array
    {
        return [
            $project->phone_number,
            $project->customer_name,
            $project->service_name,
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

    public function getHeaderRow()
    {
        return [
            'Field 1',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'G' => NumberFormat::FORMAT_DATE_YYYYMMDD,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 13,
            'C' => 20,
            'D' => 30,
            'E' => 10,
            'F' => 20,
            'G' => 20,

        ];
    }
}
