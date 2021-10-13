<?php

namespace App\Http\Controllers;

use App\Batch;
use App\BillRequest;
use App\Customer;
use App\Exports\BillExportByAccount;
use App\Exports\BillRequestExport;
use App\Imports\BillRequestImport;
use App\Jobs\BillTOpup;
use App\Jobs\ProcessDataTopup;
use App\Helpers\PhoneParse;
use Illuminate\Support\Str;
use App\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SampleExcelExport;


class BillRequestController extends Controller
{
    public function showForm()
    {
        $customers = Customer::all();
        $services = Service::all();

        return view('bill.admin.excel', compact('customers', 'services'));
    }

    public function billRequest(Request $request)
    {
        // dd($request);
        $validate = validator($request->all(), [
            'bill-file' => 'required',
            'customer' => 'required',
            'service' => 'required',
        ]);

        if ($validate->fails()) {
            return back()->withInput()->withErrors($validate);
        }

        $batch = Batch::create(
            [
                'name' => auth()->user()->name . '_' . Carbon::now(),
                'status' => 'created',
                'user_id' => auth()->id(),
                'service_id' => $request->service,
                'customer_id' => $request->customer
            ]
        );

        $import = new BillRequestImport($batch->id);
        $data = Excel::import($import, $request->file('bill-file'));
        $batch->total = $import->getRowCount();
        $batch->save();

        $first_five_numbers = BillRequest::where('batch_id', $batch->id)->take(5)->pluck('phone_number');
        $data_count = BillRequest::where('batch_id', $batch->id)->count(); //phone number count in excel file upload
        $last_five_numbers = BillRequest::where('batch_id', $batch->id)->skip($data_count - 5)->take(5)->pluck('phone_number');

        $batch_name = $batch->name;
        $batch_id = $batch->id;

        $count = BillRequest::where('batch_id', $batch_id)->count();


        $amount = Service::where('id', $request->service)->first()->amount;
        $sum =  $amount * $count;

        return view('bill.admin.confirm', compact('first_five_numbers', 'data_count', 'last_five_numbers', 'batch_name', 'batch_id', 'sum'));
    }

    public function billRequestPhoneNo(Request $request)
    {
        $validate = validator($request->all(), [
            'customer' => 'required',
            'service' => 'required',
        ]);

        if ($validate->fails()) {
            return back()->withInput()->withErrors($validate);
        }

        $batch = Batch::create(
            [
                'name' => auth()->user()->name . '_' . Carbon::now(),
                'status' => 'created',
                'user_id' => auth()->id(),
                'service_id' => $request->service,
                'customer_id' => $request->customer
            ]
        );

        $batch->total = 1;
        $batch->save();

        $billRequest = new BillRequest();
        $billRequest->reference_id = Str::uuid();
        $billRequest->phone_number = $request->phoneNo;
        $billRequest->provider = 'BP_Gate';
        $billRequest->operator = PhoneParse::getOperator($request->phoneNo);
        $billRequest->status = 'pending';
        $billRequest->batch_id = $batch->id;
        $billRequest->user_id = auth()->id();
        $billRequest->save();

        $first_five_numbers = BillRequest::where('batch_id', $batch->id)->take(5)->pluck('phone_number');
        $data_count = BillRequest::where('batch_id', $batch->id)->count(); //phone number count in excel file upload
        $last_five_numbers = BillRequest::where('batch_id', $batch->id)->skip($data_count - 5)->take(5)->pluck('phone_number');

        $batch_name = $batch->name;
        $batch_id = $batch->id;

        $count = BillRequest::where('batch_id', $batch_id)->count();


        $amount = Service::where('id', $request->service)->first()->amount;
        $sum =  $amount * $count;

        return view('bill.admin.confirm', compact('first_five_numbers', 'data_count', 'last_five_numbers', 'batch_name', 'batch_id', 'sum'));
    }

    public function billProcess(Request $request)
    {
        $processDatas = DB::table('bill_requests')
            ->select(DB::raw('bill_requests.*,customers.keyword,customers.token,services.name as service_name'))
            ->join('batches', 'bill_requests.batch_id', '=', 'batches.id')
            ->join('services', 'batches.service_id', '=', 'services.id')
            ->join('customers', 'services.customer_id', '=', 'customers.id')

            ->where(function ($query) use ($request) {
                $query->Where('bill_requests.batch_id', '=', $request->batch_id);
            })->get();

        BillTOpup::dispatch($processDatas, $request->batch_id)->delay(now()->addSeconds(3));
        // foreach ($processDatas as $data) {
        //     ProcessBillTopup::dispatch($data->phone_number, $data->reference_id, $request->batch_id, $data->keyword, $data->token, $data->service_name)->delay(now()->addSeconds(5));
        // }

        return redirect('all-bill-processed');
    }

    public function allProcessed()
    {
        $all = DB::table('bill_requests')
            ->select(DB::raw('bill_requests.*, batches.name,customers.name as customer_name,services.name as service_name'))
            ->join('batches', 'bill_requests.batch_id', '=', 'batches.id')
            ->join('services', 'batches.service_id', '=', 'services.id')
            ->join('customers', 'services.customer_id', '=', 'customers.id')
            ->orderByDesc('bill_requests.created_at')->limit(100)->get();

        return view('bill.all-processed', compact('all'));
    }

    public function myFilter(Request $request)
    {

        $from = $request->input('start_date');
        $to = $request->input('end_date');
        $phno = $request->phno;
        $status = $request->status;

        if ($request->start_date != "" && $request->end_date != "" && $request->status != "null" && $request->batchName != "") {
            $filters = DB::table('bill_requests')
                ->select(DB::raw('bill_requests.*, batches.name,customers.name as customer_name,services.name as service_name'))
                ->join('batches', 'bill_requests.batch_id', '=', 'batches.id')
                ->join('services', 'batches.service_id', '=', 'services.id')
                ->join('customers', 'batches.customer_id', '=', 'customers.id')
                ->where('bill_requests.user_id', auth()->user()->id)
                ->whereBetween('bill_requests.updated_at', [$request->start_date . ' 00:00:01', $request->end_date . ' 23:59:59'])
                ->where('batches.name', 'like', '%' . $request->batchName . '%')
                ->where('bill_requests.status', $request->status)
                ->get();
        } elseif ($request->start_date != "" && $request->end_date != "" && $request->status == "null" && $request->batchName == "") {
            $filters = DB::table('bill_requests')
                ->select(DB::raw('bill_requests.*, batches.name,customers.name as customer_name,services.name as service_name'))
                ->join('batches', 'bill_requests.batch_id', '=', 'batches.id')
                ->join('services', 'batches.service_id', '=', 'services.id')
                ->join('customers', 'batches.customer_id', '=', 'customers.id')
                ->where('bill_requests.user_id', auth()->user()->id)
                ->whereBetween('bill_requests.updated_at', [$request->start_date . ' 00:00:01', $request->end_date . ' 23:59:59'])
                ->get();
        } elseif ($request->start_date != "" && $request->end_date != "" && $request->status != "null" && $request->batchName == "") {
            $filters = DB::table('bill_requests')
                ->select(DB::raw('bill_requests.*, batches.name,customers.name as customer_name,services.name as service_name'))
                ->join('batches', 'bill_requests.batch_id', '=', 'batches.id')
                ->join('services', 'batches.service_id', '=', 'services.id')
                ->join('customers', 'batches.customer_id', '=', 'customers.id')
                ->where('bill_requests.user_id', auth()->user()->id)
                ->whereBetween('bill_requests.updated_at', [$request->start_date . ' 00:00:01', $request->end_date . ' 23:59:59'])
                ->where('bill_requests.status', $request->status)
                ->get();
        } elseif ($request->start_date != "" && $request->end_date != "" && $request->status == "null" && $request->batchName != "") {
            $filters = DB::table('bill_requests')
                ->select(DB::raw('bill_requests.*, batches.name,customers.name as customer_name,services.name as service_name'))
                ->join('batches', 'bill_requests.batch_id', '=', 'batches.id')
                ->join('services', 'batches.service_id', '=', 'services.id')
                ->join('customers', 'batches.customer_id', '=', 'customers.id')
                ->where('bill_requests.user_id', auth()->user()->id)
                ->whereBetween('bill_requests.updated_at', [$request->start_date . ' 00:00:01', $request->end_date . ' 23:59:59'])
                ->where('batches.name', 'like', '%' . $request->batchName . '%')
                ->get();
        } elseif ($request->start_date == "" && $request->end_date == "" && $request->status != "null" && $request->batchName != "") {
            $filters = DB::table('bill_requests')
                ->select(DB::raw('bill_requests.*, batches.name,customers.name as customer_name,services.name as service_name'))
                ->join('batches', 'bill_requests.batch_id', '=', 'batches.id')
                ->join('services', 'batches.service_id', '=', 'services.id')
                ->join('customers', 'batches.customer_id', '=', 'customers.id')
                ->where('bill_requests.user_id', auth()->user()->id)
                ->where('batches.name', 'like', '%' . $request->batchName . '%')
                ->where('bill_requests.status', $request->status)

                ->get();
        } elseif ($request->start_date == "" && $request->end_date == "" && $request->status != "null" && $request->batchName == "") {
            $filters = DB::table('bill_requests')
                ->select(DB::raw('bill_requests.*, batches.name,customers.name as customer_name,services.name as service_name'))
                ->join('batches', 'bill_requests.batch_id', '=', 'batches.id')
                ->join('services', 'batches.service_id', '=', 'services.id')
                ->join('customers', 'batches.customer_id', '=', 'customers.id')
                ->where('bill_requests.user_id', auth()->user()->id)
                ->where('bill_requests.status', $request->status)
                ->get();
        } elseif ($request->start_date == "" && $request->end_date == "" && $request->status == "null" && $request->batchName != "") {
            $filters = DB::table('bill_requests')
                ->select(DB::raw('bill_requests.*, batches.name,customers.name as customer_name,services.name as service_name'))
                ->join('batches', 'bill_requests.batch_id', '=', 'batches.id')
                ->join('services', 'batches.service_id', '=', 'services.id')
                ->join('customers', 'batches.customer_id', '=', 'customers.id')
                ->where('bill_requests.user_id', auth()->user()->id)
                ->where('batches.name', 'like', '%' . $request->batchName . '%')
                ->get();
        } else {
            $filters = DB::table('bill_requests')
                ->select(DB::raw('bill_requests.*, batches.name,customers.name as customer_name,services.name as service_name'))
                ->join('batches', 'bill_requests.batch_id', '=', 'batches.id')
                ->join('services', 'batches.service_id', '=', 'services.id')
                ->join('customers', 'batches.customer_id', '=', 'customers.id')
                ->where('bill_requests.user_id', auth()->user()->id)
                ->get();
        }

        $batch_name = $request->batchName;


        if ($request->action == "Search") {

            return view('bill.processed', compact('filters', 'from', 'to', 'phno', 'status', 'batch_name'));
        } elseif ($request->action == "Export") {

            return Excel::download(new BillExportByAccount($filters), 'bill_export.xlsx');
        } else {
            return redirect('my-bill-processed');
        }
    }

    public function allFilter(Request $request)
    {
        // dd($request);
        $from = $request->input('start_date');
        $to = $request->input('end_date');
        $phno = $request->phno;
        $status = $request->status;

        if ($request->start_date != "" && $request->end_date != "" && $request->status != "null" && $request->batchName != "") {
            $filters = DB::table('bill_requests')
                ->select(DB::raw('bill_requests.*, batches.name,customers.name as customer_name,services.name as service_name'))
                ->join('batches', 'bill_requests.batch_id', '=', 'batches.id')
                ->join('services', 'batches.service_id', '=', 'services.id')
                ->join('customers', 'batches.customer_id', '=', 'customers.id')
                ->whereBetween('bill_requests.updated_at', [$request->start_date . ' 00:00:01', $request->end_date . ' 23:59:59'])
                ->where('batches.name', 'like', '%' . $request->batchName . '%')
                ->where('bill_requests.status', $request->status)
                ->get();
        } elseif ($request->start_date != "" && $request->end_date != "" && $request->status == "null" && $request->batchName == "") {
            $filters = DB::table('bill_requests')
                ->select(DB::raw('bill_requests.*, batches.name,customers.name as customer_name,services.name as service_name'))
                ->join('batches', 'bill_requests.batch_id', '=', 'batches.id')
                ->join('services', 'batches.service_id', '=', 'services.id')
                ->join('customers', 'batches.customer_id', '=', 'customers.id')
                ->whereBetween('bill_requests.updated_at', [$request->start_date . ' 00:00:01', $request->end_date . ' 23:59:59'])
                ->get();
        } elseif ($request->start_date != "" && $request->end_date != "" && $request->status != "null" && $request->batchName == "") {
            $filters = DB::table('bill_requests')
                ->select(DB::raw('bill_requests.*, batches.name,customers.name as customer_name,services.name as service_name'))
                ->join('batches', 'bill_requests.batch_id', '=', 'batches.id')
                ->join('services', 'batches.service_id', '=', 'services.id')
                ->join('customers', 'batches.customer_id', '=', 'customers.id')
                ->whereBetween('bill_requests.updated_at', [$request->start_date . ' 00:00:01', $request->end_date . ' 23:59:59'])
                ->where('bill_requests.status', $request->status)
                ->get();
        } elseif ($request->start_date != "" && $request->end_date != "" && $request->status == "null" && $request->batchName != "") {
            $filters = DB::table('bill_requests')
                ->select(DB::raw('bill_requests.*, batches.name,customers.name as customer_name,services.name as service_name'))
                ->join('batches', 'bill_requests.batch_id', '=', 'batches.id')
                ->join('services', 'batches.service_id', '=', 'services.id')
                ->join('customers', 'batches.customer_id', '=', 'customers.id')
                ->whereBetween('bill_requests.updated_at', [$request->start_date . ' 00:00:01', $request->end_date . ' 23:59:59'])
                ->where('batches.name', 'like', '%' . $request->batchName . '%')
                ->get();
        } elseif ($request->start_date == "" && $request->end_date == "" && $request->status != "null" && $request->batchName != "") {
            $filters = DB::table('bill_requests')
                ->select(DB::raw('bill_requests.*, batches.name,customers.name as customer_name,services.name as service_name'))
                ->join('batches', 'bill_requests.batch_id', '=', 'batches.id')
                ->join('services', 'batches.service_id', '=', 'services.id')
                ->join('customers', 'batches.customer_id', '=', 'customers.id')
                ->where('batches.name', 'like', '%' . $request->batchName . '%')
                ->where('bill_requests.status', $request->status)

                ->get();
        } elseif ($request->start_date == "" && $request->end_date == "" && $request->status != "null" && $request->batchName == "") {
            $filters = DB::table('bill_requests')
                ->select(DB::raw('bill_requests.*, batches.name,customers.name as customer_name,services.name as service_name'))
                ->join('batches', 'bill_requests.batch_id', '=', 'batches.id')
                ->join('services', 'batches.service_id', '=', 'services.id')
                ->join('customers', 'batches.customer_id', '=', 'customers.id')
                ->where('bill_requests.status', $request->status)
                ->get();
        } elseif ($request->start_date == "" && $request->end_date == "" && $request->status == "null" && $request->batchName != "") {
            $filters = DB::table('bill_requests')
                ->select(DB::raw('bill_requests.*, batches.name,customers.name as customer_name,services.name as service_name'))
                ->join('batches', 'bill_requests.batch_id', '=', 'batches.id')
                ->join('services', 'batches.service_id', '=', 'services.id')
                ->join('customers', 'batches.customer_id', '=', 'customers.id')
                ->where('batches.name', 'like', '%' . $request->batchName . '%')
                ->get();
        } else {
            $filters = DB::table('bill_requests')
                ->select(DB::raw('bill_requests.*, batches.name,customers.name as customer_name,services.name as service_name'))
                ->join('batches', 'bill_requests.batch_id', '=', 'batches.id')
                ->join('services', 'batches.service_id', '=', 'services.id')
                ->join('customers', 'batches.customer_id', '=', 'customers.id')
                ->get();
        }

        $batch_name = $request->batchName;
        if ($request->action == "Search") {

            return view('bill.all-processed', compact('filters', 'from', 'to', 'phno', 'status', 'batch_name'));
        } elseif ($request->action == "Export") {

            return Excel::download(new BillRequestExport($filters), 'all_bill_export.xlsx');
        } else {
            return redirect('all-bill-processed');
        }
    }


    public function getServices(Request $request)
    {
        $getservices = DB::table('services')->where('customer_id', $request->id)->where('name', '!=', "Data")->get();


        return response()
            ->json($getservices);
    }

    public function sampleExcelDownload()
    {
        return Excel::download(new SampleExcelExport, 'sample_excel.xlsx');
    }
}
