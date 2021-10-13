<?php

namespace App\Http\Controllers;

use App\Batch;
use App\Package;
use App\Service;
use App\BillRequest;
use App\DataRequest;
use App\Exports\BatchExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class BatchController extends Controller
{
    public function batches(Request $request)
    {
        // $batches = DB::table('batches')
        //     ->select(DB::raw('batches.*,customers.name as customer_name'))
        //     ->join('customers', 'batches.customer_id', '=', 'customers.id')
        //     ->where(function ($query) use ($request) {
        //         $query->Where('batches.user_id', '=', auth()->user()->id);
        //     })->orderByDesc('batches.created_at')->get();

        $batches = DB::select(DB::raw("
            select b.*, s.amount as btotal, p.price as dtotal, c.name as cname
            from batches b
            left join customers c on c.id = b.customer_id
            left join services s on s.id = b.service_id
            left join data_requests d on d.batch_id = b.id
            left join packages p on p.id = d.package_id
            where b.user_id = " . auth()->user()->id . "
            order by b.id desc;
        "));
        // $batches = Batch::where('user_id',auth()->user()->id)->get();
        return view('batch.my-batches', compact('batches'));
    }

    public function allBatches(Request $request)
    {
        // $batches = DB::table('batches')
        //     ->select(DB::raw('batches.*,customers.name as customer_name'))
        //     ->join('customers', 'batches.customer_id', '=', 'customers.id')
        //     })->orderByDesc('batches.created_at')->get();

        $batches = DB::select(DB::raw("
            select b.*, s.amount as btotal, p.price as dtotal, c.name as cname
            from batches b
            left join customers c on c.id = b.customer_id
            left join services s on s.id = b.service_id
            left join data_requests d on d.batch_id = b.id
            left join packages p on p.id = d.package_id
            order by b.id desc;
        "));
        // dd($batches);
        return view('batch.all-batches', compact('batches'));
    }

    public function batchDetail(Request $request, $batch_id)
    {
        $batch = Batch::find($batch_id);
        // dd($batch->service_id);
        $services = Service::where('id', $batch->service_id)->get();
        $packages = DB::select(DB::raw("
            select p.* from packages p
            inner join data_requests dr on dr.package_id = p.id
            inner join batches b on b.id = dr.batch_id
            where b.id = $batch_id;
        "));
        $bill_data = BillRequest::where('batch_id', $batch_id)->where('status', '!=', 'success')->select('reference_id', 'phone_number')->get();
        $data = DataRequest::where('batch_id', $batch_id)->where('status', '!=', 'success')->pluck('phone_number');

        return view('batch.detail', compact('batch', 'bill_data', 'services', 'data', 'batch_id', 'packages'));
    }

    public function allExport($batch_id)
    {
        return Excel::download(new BatchExport($batch_id), 'batch.xlsx');
    }
}
