<?php

namespace App\Http\Controllers;

use App\Batch;
use App\Package;
use App\Customer;
use Carbon\Carbon;
use App\DataRequest;
use Illuminate\Http\Request;
use App\Jobs\ProcessDataTopup;
use App\Exports\DataRequestExport;
use Illuminate\Support\Facades\DB;
// use App\Imports\DataRequestsImport;
use App\Exports\DataExportByAccount;
use Maatwebsite\Excel\Facades\Excel;
use App\Service;
use App\Imports\DataRequestImport;
use App\Helpers\PhoneParse;
use Illuminate\Support\Str;

class DataRequestController extends Controller
{
    public function showForm()
    {
        $customers = Customer::all();
        $mpt = DB::table('packages')->where('operator', 'MPT')->get();
        $telenor = DB::table('packages')->where('operator', 'Telenor')->get();
        $ooredoo = DB::table('packages')->where('operator', 'Ooredoo')->get();
        $mytel = DB::table('packages')->where('operator', 'mytel')->get();
        return view('data.admin.excel', compact('customers', 'mpt', 'telenor', 'ooredoo', 'mytel'));
    }


    public function dataRequest(Request $request)
    {
        $validate = validator($request->all(), [
            'data-file' => 'required',
            'customer' => 'required'
        ]);

        if ($validate->fails()) {
            return back()->withInput()->withErrors($validate);
        }

        $service = Service::where('name', 'Data')->first()->id;

        $batch = Batch::create([
            'name' => auth()->user()->name . '_' . Carbon::now(),
            'status' => 'created',
            'user_id' => auth()->id(),
            'service_id' => $service,
            'customer_id' => $request->customer
        ]);


        $import = new DataRequestImport($batch->id, $request->mpt, $request->ooredoo, $request->telenor, $request->mytel);
        $data = Excel::import($import, $request->file('data-file'));

        $batch->total = $import->getRowCount();
        $batch->save();

        $first_five_numbers = DataRequest::where('batch_id', $batch->id)->take(5)->pluck('phone_number');
        $data_count = DataRequest::where('batch_id', $batch->id)->count();       //phone number count in excel file upload
        $last_five_numbers = DataRequest::where('batch_id', $batch->id)->skip($data_count - 5)->take(5)->pluck('phone_number');

        $batch_name = $batch->name;
        $batch_id = $batch->id;

        $getlastbatchId = DataRequest::where('batch_id', $batch_id)->get();
        $getpackageId = [];
        foreach ($getlastbatchId as $key => $value) {
            array_push($getpackageId, $value->package_id);
        }

        $amount = Package::whereIn('id', $getpackageId)
            ->get();

        // dd($amount);

        $prices = [];
        foreach ($amount as $key => $value) {
            array_push($prices, (int)$value->price);
        }

        $sum = array_sum($prices);



        $packages = Package::whereIn('id', $getpackageId)->get();
        // dd($packages);

        return view('data.admin.confirm', compact('first_five_numbers', 'data_count', 'last_five_numbers', 'batch_name', 'batch_id', 'sum', 'packages'));
    }

    public function dataRequestPhoneNo(Request $request)
    {
        // dd($request);
        $validate = validator($request->all(), [
            'phoneNo' => 'required',
            'package' => 'required',
            'customer' => 'required'
        ]);

        if ($validate->fails()) {
            return back()->withInput()->withErrors($validate);
        }

        $service = Service::where('name', 'Data')->first()->id;

        $batch = Batch::create([
            'name' => auth()->user()->name . '_' . Carbon::now(),
            'status' => 'created',
            'user_id' => auth()->id(),
            'service_id' => $service,
            'customer_id' => $request->customer
        ]);

        $batch->total = 1;
        $batch->save();

        $dataRequest = new DataRequest();
        $dataRequest->reference_id = Str::uuid();
        $dataRequest->phone_number = $request->phoneNo;
        $dataRequest->provider = 'BP_Gate';
        $dataRequest->operator = PhoneParse::getOperator($request->phoneNo);
        $dataRequest->status = 'pending';
        $dataRequest->batch_id = $batch->id;
        $dataRequest->user_id = auth()->id();
        $dataRequest->package_id = $request->package;
        $dataRequest->save();

        $first_five_numbers = DataRequest::where('batch_id', $batch->id)->take(5)->pluck('phone_number');
        $data_count = DataRequest::where('batch_id', $batch->id)->count();       //phone number count in excel file upload
        $last_five_numbers = DataRequest::where('batch_id', $batch->id)->skip($data_count - 5)->take(5)->pluck('phone_number');

        $batch_name = $batch->name;
        $batch_id = $batch->id;

        $getlastbatchId = DataRequest::where('batch_id', $batch_id)->get();
        $getpackageId = [];
        foreach ($getlastbatchId as $key => $value) {
            array_push($getpackageId, $value->package_id);
        }

        $amount = Package::whereIn('id', $getpackageId)
            ->get();

        // dd($amount);

        $prices = [];
        foreach ($amount as $key => $value) {
            array_push($prices, (int)$value->price);
        }

        $sum = array_sum($prices);



        $packages = Package::whereIn('id', $getpackageId)->get();
        // dd($packages);

        return view('data.admin.confirm', compact('first_five_numbers', 'data_count', 'last_five_numbers', 'batch_name', 'batch_id', 'sum', 'packages'));
    }

    public function dataProcess(Request $request)
    {
        $processDatas = DB::table('data_requests')
            ->select(DB::raw('data_requests.*,customers.keyword,customers.token, packages.package_name, packages.package_code,services.name as service_name'))
            ->join('batches', 'data_requests.batch_id', '=', 'batches.id')
            ->join('services', 'batches.service_id', '=', 'services.id')
            ->join('customers', 'services.customer_id', '=', 'customers.id')
            ->join('packages', 'packages.id', '=', 'data_requests.package_id')
            ->where(function ($query) use ($request) {
                $query->Where('data_requests.batch_id', '=', $request->batch_id);
            })->get();

        ProcessDataTopup::dispatch($processDatas, $request->batch_id)->delay(now()->addSeconds(3));
        // dd($processDatas);
        // foreach ($processDatas as $data) {
        //     ProcessDataTopup::dispatch($data->phone_number, $data->reference_id, $request->batch_id, $data->keyword, $data->token, $data->package_name, $data->package_code, $data->service_name)->delay(now()->addSeconds(1));
        // }
        return redirect('all-data-processed');
    }

    public function allProcessed()
    {
        $all = DB::table('data_requests')
            ->select(DB::raw('data_requests.*, batches.name,customers.name as customer_name,packages.package_name, volume'))
            ->join('batches', 'data_requests.batch_id', '=', 'batches.id')
            ->join('customers', 'batches.customer_id', '=', 'customers.id')
            ->join('packages', 'data_requests.package_id', '=', 'packages.id')
            ->orderByDesc('data_requests.created_at')->limit(100)->get();



        return view('data.all-processed', compact('all'));
    }

    public function myFilter(Request $request)
    {
        $from = $request->input('start_date');
        $to = $request->input('end_date');
        $phno = $request->phno;
        $status = $request->status;

        if ($request->start_date != "" && $request->end_date != "" && $request->status != "null" && $request->batchName != "") {
            $filters = DB::table('data_requests')
                ->select(DB::raw('data_requests.*, batches.name,customers.name as customer_name,packages.package_name, packages.volume'))
                ->join('batches', 'data_requests.batch_id', '=', 'batches.id')
                ->join('customers', 'batches.customer_id', '=', 'customers.id')
                ->join('packages', 'data_requests.package_id', '=', 'packages.id')
                ->where('data_requests.user_id', auth()->user()->id)
                ->whereBetween('data_requests.updated_at', [$request->start_date . ' 00:00:01', $request->end_date . ' 23:59:59'])
                ->where('batches.name', 'like', '%' . $request->batchName . '%')
                ->where('data_requests.status', $request->status)
                ->get();
        } elseif ($request->start_date != "" && $request->end_date != "" && $request->status == "null" && $request->batchName == "") {
            $filters = DB::table('data_requests')
                ->select(DB::raw('data_requests.*, batches.name,customers.name as customer_name,packages.package_name, packages.volume'))
                ->join('batches', 'data_requests.batch_id', '=', 'batches.id')
                ->join('customers', 'batches.customer_id', '=', 'customers.id')
                ->join('packages', 'data_requests.package_id', '=', 'packages.id')
                ->where('data_requests.user_id', auth()->user()->id)
                ->whereBetween('data_requests.updated_at', [$request->start_date . ' 00:00:01', $request->end_date . ' 23:59:59'])
                ->get();
        } elseif ($request->start_date != "" && $request->end_date != "" && $request->status == "null" && $request->batchName != "") {
            $filters = DB::table('data_requests')
                ->select(DB::raw('data_requests.*, batches.name,customers.name as customer_name,packages.package_name, packages.volume'))
                ->join('batches', 'data_requests.batch_id', '=', 'batches.id')
                ->join('customers', 'batches.customer_id', '=', 'customers.id')
                ->join('packages', 'data_requests.package_id', '=', 'packages.id')
                ->where('data_requests.user_id', auth()->user()->id)
                ->whereBetween('data_requests.updated_at', [$request->start_date . ' 00:00:01', $request->end_date . ' 23:59:59'])
                ->where('batches.name', 'like', '%' . $request->batchName . '%')
                ->get();
        } elseif ($request->start_date != "" && $request->end_date != "" && $request->status != "null" && $request->batchName == "") {
            $filters = DB::table('data_requests')
                ->select(DB::raw('data_requests.*, batches.name,customers.name as customer_name,packages.package_name, packages.volume'))
                ->join('batches', 'data_requests.batch_id', '=', 'batches.id')
                ->join('customers', 'batches.customer_id', '=', 'customers.id')
                ->join('packages', 'data_requests.package_id', '=', 'packages.id')
                ->where('data_requests.user_id', auth()->user()->id)
                ->whereBetween('data_requests.updated_at', [$request->start_date . ' 00:00:01', $request->end_date . ' 23:59:59'])
                ->where('data_requests.status', $request->status)
                ->get();
        } elseif ($request->start_date == "" && $request->end_date == "" && $request->status != "null" && $request->batchName != "") {
            $filters = DB::table('data_requests')
                ->select(DB::raw('data_requests.*, batches.name,customers.name as customer_name,packages.package_name, packages.volume'))
                ->join('batches', 'data_requests.batch_id', '=', 'batches.id')
                ->join('customers', 'batches.customer_id', '=', 'customers.id')
                ->join('packages', 'data_requests.package_id', '=', 'packages.id')
                ->where('data_requests.user_id', auth()->user()->id)
                ->where('batches.name', 'like', '%' . $request->batchName . '%')
                ->where('data_requests.status', $request->status)
                ->get();
        } elseif ($request->start_date == "" && $request->end_date == "" && $request->status == "null" && $request->batchName != "") {
            $filters = DB::table('data_requests')
                ->select(DB::raw('data_requests.*, batches.name,customers.name as customer_name,packages.package_name, packages.volume'))
                ->join('batches', 'data_requests.batch_id', '=', 'batches.id')
                ->join('customers', 'batches.customer_id', '=', 'customers.id')
                ->join('packages', 'data_requests.package_id', '=', 'packages.id')
                ->where('data_requests.user_id', auth()->user()->id)
                ->where('batches.name', 'like', '%' . $request->batchName . '%')
                ->get();
        } elseif ($request->start_date == "" && $request->end_date == "" && $request->status != "null") {
            $filters = DB::table('data_requests')
                ->select(DB::raw('data_requests.*, batches.name,customers.name as customer_name,packages.package_name, packages.volume'))
                ->join('batches', 'data_requests.batch_id', '=', 'batches.id')
                ->join('customers', 'batches.customer_id', '=', 'customers.id')
                ->join('packages', 'data_requests.package_id', '=', 'packages.id')
                ->where('data_requests.user_id', auth()->user()->id)
                ->where('data_requests.status', $request->status)
                ->get();
        } else {
            $filters = DB::table('data_requests')
                ->select(DB::raw('data_requests.*, batches.name,customers.name as customer_name,packages.package_name, packages.volume'))
                ->join('batches', 'data_requests.batch_id', '=', 'batches.id')
                ->join('customers', 'batches.customer_id', '=', 'customers.id')
                ->join('packages', 'data_requests.package_id', '=', 'packages.id')
                ->where('data_requests.user_id', auth()->user()->id)
                ->get();
        }

        $batch_name = $request->batchName;

        if ($request->action == "Search") {

            return view('data.processed', compact('filters', 'from', 'to', 'phno', 'status', 'batch_name'));
        } elseif ($request->action == "Export") {

            return Excel::download(new DataExportByAccount($filters), 'all_bill_export.xlsx');
        } else {
            return redirect('my-data-processed');
        }
    }

    public function allFilter(Request $request)
    {

        $from = $request->input('start_date');
        $to = $request->input('end_date');
        $phno = $request->phno;
        $status = $request->status;

        if ($request->start_date != "" && $request->end_date != "" && $request->status != "null" && $request->batchName != "") {
            $filters = DB::table('data_requests')
                ->select(DB::raw('data_requests.*, batches.name,customers.name as customer_name,packages.package_name, packages.volume'))
                ->join('batches', 'data_requests.batch_id', '=', 'batches.id')
                ->join('customers', 'batches.customer_id', '=', 'customers.id')
                ->join('packages', 'data_requests.package_id', '=', 'packages.id')
                ->whereBetween('data_requests.updated_at', [$request->start_date . ' 00:00:01', $request->end_date . ' 23:59:59'])
                ->where('batches.name', 'like', '%' . $request->batchName . '%')
                ->where('data_requests.status', $request->status)
                ->get();
        } elseif ($request->start_date != "" && $request->end_date != "" && $request->status == "null" && $request->batchName == "") {
            $filters = DB::table('data_requests')
                ->select(DB::raw('data_requests.*, batches.name,customers.name as customer_name,packages.package_name, packages.volume'))
                ->join('batches', 'data_requests.batch_id', '=', 'batches.id')
                ->join('customers', 'batches.customer_id', '=', 'customers.id')
                ->join('packages', 'data_requests.package_id', '=', 'packages.id')
                ->whereBetween('data_requests.updated_at', [$request->start_date . ' 00:00:01', $request->end_date . ' 23:59:59'])
                ->get();
        } elseif ($request->start_date != "" && $request->end_date != "" && $request->status == "null" && $request->batchName != "") {
            $filters = DB::table('data_requests')
                ->select(DB::raw('data_requests.*, batches.name,customers.name as customer_name,packages.package_name, packages.volume'))
                ->join('batches', 'data_requests.batch_id', '=', 'batches.id')
                ->join('customers', 'batches.customer_id', '=', 'customers.id')
                ->join('packages', 'data_requests.package_id', '=', 'packages.id')
                ->whereBetween('data_requests.updated_at', [$request->start_date . ' 00:00:01', $request->end_date . ' 23:59:59'])
                ->where('batches.name', 'like', '%' . $request->batchName . '%')
                ->get();
        } elseif ($request->start_date != "" && $request->end_date != "" && $request->status != "null" && $request->batchName == "") {
            $filters = DB::table('data_requests')
                ->select(DB::raw('data_requests.*, batches.name,customers.name as customer_name,packages.package_name, packages.volume'))
                ->join('batches', 'data_requests.batch_id', '=', 'batches.id')
                ->join('customers', 'batches.customer_id', '=', 'customers.id')
                ->join('packages', 'data_requests.package_id', '=', 'packages.id')
                ->whereBetween('data_requests.updated_at', [$request->start_date . ' 00:00:01', $request->end_date . ' 23:59:59'])
                ->where('data_requests.status', $request->status)
                ->get();
        } elseif ($request->start_date == "" && $request->end_date == "" && $request->status != "null" && $request->batchName != "") {
            $filters = DB::table('data_requests')
                ->select(DB::raw('data_requests.*, batches.name,customers.name as customer_name,packages.package_name, packages.volume'))
                ->join('batches', 'data_requests.batch_id', '=', 'batches.id')
                ->join('customers', 'batches.customer_id', '=', 'customers.id')
                ->join('packages', 'data_requests.package_id', '=', 'packages.id')
                ->where('batches.name', 'like', '%' . $request->batchName . '%')
                ->where('data_requests.status', $request->status)
                ->get();
        } elseif ($request->start_date == "" && $request->end_date == "" && $request->status == "null" && $request->batchName != "") {
            $filters = DB::table('data_requests')
                ->select(DB::raw('data_requests.*, batches.name,customers.name as customer_name,packages.package_name, packages.volume'))
                ->join('batches', 'data_requests.batch_id', '=', 'batches.id')
                ->join('customers', 'batches.customer_id', '=', 'customers.id')
                ->join('packages', 'data_requests.package_id', '=', 'packages.id')
                ->where('batches.name', 'like', '%' . $request->batchName . '%')
                ->get();
        } elseif ($request->start_date == "" && $request->end_date == "" && $request->status != "null") {
            $filters = DB::table('data_requests')
                ->select(DB::raw('data_requests.*, batches.name,customers.name as customer_name,packages.package_name, packages.volume'))
                ->join('batches', 'data_requests.batch_id', '=', 'batches.id')
                ->join('customers', 'batches.customer_id', '=', 'customers.id')
                ->join('packages', 'data_requests.package_id', '=', 'packages.id')
                ->where('data_requests.status', $request->status)
                ->get();
        } else {
            $filters = DB::table('data_requests')
                ->select(DB::raw('data_requests.*, batches.name,customers.name as customer_name,packages.package_name, packages.volume'))
                ->join('batches', 'data_requests.batch_id', '=', 'batches.id')
                ->join('customers', 'batches.customer_id', '=', 'customers.id')
                ->join('packages', 'data_requests.package_id', '=', 'packages.id')
                ->get();
        }

        $batch_name = $request->batchName;

        if ($request->action == "Search") {

            return view('data.all-processed', compact('filters', 'from', 'to', 'phno', 'status', 'batch_name'));
        } elseif ($request->action == "Export") {

            return Excel::download(new DataRequestExport($filters), 'all_bill_export.xlsx');
        } else {
            return redirect('all-data-processed');
        }
    }
}
