<?php

namespace App\Http\Controllers;

use App\Batch;
use App\Package;
use App\Service;
use App\Customer;
use Carbon\Carbon;
use App\DataRequest;
use Illuminate\Http\Request;
use App\Jobs\ProcessDataTopup;
use Illuminate\Support\Facades\DB;
use App\Imports\DataRequestImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Helpers\PhoneParse;
use Illuminate\Support\Str;

class UserDataRequestController extends Controller
{
    public function show()
    {
        $customer_id = Customer::where('added_user_id', auth()->id())->first()->id;
        $mpt = DB::table('packages')->where('operator', 'MPT')->get();
        $telenor = DB::table('packages')->where('operator', 'Telenor')->get();
        $ooredoo = DB::table('packages')->where('operator', 'Ooredoo')->get();
        $mytel = DB::table('packages')->where('operator', 'mytel')->get();
        return view('data.user.excel', compact('customer_id', 'mpt', 'telenor', 'ooredoo', 'mytel'));
    }

    // public function checkValue(Request $request)
    // {
    //     if ($request->radioValue == 'mpt') {
    //         $mpt_package = Package::where("operator", "MPT")->get();
    //         return response()->json(['data' => $mpt_package]);
    //     } elseif ($request->radioValue == 'ooredoo') {
    //         $ooredoo_package = Package::where("operator", "Ooredoo")->get();
    //         return response()->json(['data' => $ooredoo_package]);
    //     } elseif ($request->radioValue == 'telenor') {
    //         $telenor_package = Package::where("operator", "Telenor")->get();
    //         return response()->json(['data' => $telenor_package]);
    //     } else {
    //         $mytel_package = Package::where("operator", "MyTel")->get();
    //         return response()->json(['data' => $mytel_package]);
    //     }
    // }

    public function dataRequest(Request $request)
    {
        $validate = validator($request->all(), [
            'file' => 'required'
        ]);

        if ($validate->fails()) {
            return back()->withInput()->withErrors($validate);
        }

        $service = Service::where('name', 'Data')->first()->id;

        $batch =  Batch::create(
            [
                'name' => auth()->user()->name . '_' . Carbon::now(),
                'status' => 'created',
                'user_id' => auth()->id(),
                'service_id' => $service,
                'customer_id' => $request->customer_id
            ]
        );


        $import = new DataRequestImport($batch->id, $request->mpt, $request->ooredoo, $request->telenor, $request->mytel);
        $data = Excel::import($import, $request->file('file'));   //phone number count in excel file upload        
        $batch->total = $import->getRowCount();
        $batch->save();

        $first_five_numbers = DataRequest::where('batch_id', $batch->id)->take(5)->pluck('phone_number');
        $data_count = DataRequest::where('batch_id', $batch->id)->count();
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

        return view('data.user.confirm', compact('first_five_numbers', 'data_count', 'last_five_numbers', 'batch_name', 'batch_id', 'sum', 'packages'));
    }

    public function dataRequestPhoneNo(Request $request)
    {
        $validate = validator($request->all(), [
            'phoneNo' => 'required',
            'package' => 'required',
        ]);

        if ($validate->fails()) {
            return back()->withInput()->withErrors($validate);
        }

        $service = Service::where('name', 'Data')->first()->id;

        $batch =  Batch::create(
            [
                'name' => auth()->user()->name . '_' . Carbon::now(),
                'status' => 'created',
                'user_id' => auth()->id(),
                'service_id' => $service,
                'customer_id' => $request->customer_id
            ]
        );

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
        $data_count = DataRequest::where('batch_id', $batch->id)->count();
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

        return view('data.user.confirm', compact('first_five_numbers', 'data_count', 'last_five_numbers', 'batch_name', 'batch_id', 'sum', 'packages'));
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
        //     ProcessDataTopup::dispatch($data->phone_number, $data->reference_id, $request->batch_id, $data->keyword, $data->token, $data->package_name, $data->package_code, $data->service_name)->delay(now()->addSeconds(5));
        // }
        return redirect('my-data-processed');
    }

    public function Processed(Request $request)
    {
        // $datas = DB::table('data_requests')
        //     ->select(DB::raw('data_requests.*, batches.name,customers.name as customer_name,packages.package_name, volume'))
        //     ->join('batches', 'data_requests.batch_id', '=', 'batches.id')
        //     ->join('packages', 'batches.package_id', '=', 'packages.id')
        //     ->join('customers', 'batches.customer_id', '=', 'customers.id')
        //     ->where(function ($query) use ($request) {
        //         $query->Where('data_requests.user_id', '=', auth()->user()->id);
        //     })->orderByDesc('data_requests.id')->get();
        $datas = DB::select(DB::raw("
            select dr.id,dr.description, dr.phone_number, c.name as customer_name, p.package_name,p.volume, b.name, dr.status, dr.created_at, dr.updated_at
            from data_requests dr
            inner join batches b on b.id = dr.batch_id
            inner join customers c on c.id = b.customer_id
            inner join packages p on p.id = dr.package_id
            where dr.user_id = " . auth()->user()->id .
            " order by dr.created_at desc limit 100;
        "));
        return view('data.processed', compact('datas'));
    }
}
