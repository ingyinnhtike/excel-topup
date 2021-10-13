<?php

namespace App\Exports;

use App\Batch;
use App\BillRequest;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class BatchExport implements FromCollection, WithHeadings,WithMapping, WithColumnFormatting
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($batch_id)
    {
        $this->batch_id = $batch_id;
    }

    public function collection()
    {
        if(BillRequest::where('batch_id',$this->batch_id)->exists())
        {
            return DB::table('bill_requests')->when($this->batch_id, function ($query, $batch_id){
                return $query->where('batch_id', $batch_id);
            })       
            ->select(DB::raw('bill_requests.phone_number,bill_requests.status,bill_requests.created_at,bill_requests.updated_at, batches.name,customers.name as customer_name,services.name as service_name'))
            ->join('batches', 'bill_requests.batch_id', '=', 'batches.id')
            ->join('services', 'batches.service_id', '=', 'services.id')
            ->join('customers', 'services.customer_id', '=', 'customers.id')
            ->get();
        }
        else{
            return DB::table('data_requests')->when($this->batch_id, function ($query, $batch_id){
                return $query->where('batch_id', $batch_id);
            })       
            ->select(DB::raw('data_requests.phone_number,data_requests.status,data_requests.created_at,data_requests.updated_at, batches.name,customers.name as customer_name,services.name as service_name'))
            ->join('batches', 'data_requests.batch_id', '=', 'batches.id')
            ->join('services', 'batches.service_id', '=', 'services.id')
            ->join('customers', 'services.customer_id', '=', 'customers.id')
            ->get();
        }
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

    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'G' => NumberFormat::FORMAT_DATE_YYYYMMDD,
        ];
    }

}
