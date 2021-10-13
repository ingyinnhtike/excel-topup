<?php

namespace App\Http\Controllers;

use App\Batch;
use App\Service;
use Carbon\Carbon;
use App\Customer;
use App\BillRequest;
use Illuminate\Http\Request;
use App\Jobs\BillTOpup;
use Illuminate\Support\Facades\DB;
use App\Imports\BillRequestImport;
use Maatwebsite\Excel\Facades\Excel;
use Auth;
use App\Helpers\PhoneParse;
use Illuminate\Support\Str;

class UserBillRequestController extends Controller
{
    public function show()
    {
        $customer_id = Customer::where('added_user_id', auth()->id())->first()->id;
        $services = Service::whereHas('customer', function ($q) {
            $q->where('added_user_id', auth()->user()->id);
        })->get();

        // $services = Customer::with('services')->where('added_user_id', auth()->user()->id)->get();
        return view('bill.user.excel', compact('services', 'customer_id'));
    }

    public function billRequest(Request $request)
    {
        $validate = validator($request->all(), [
            'file' => 'required',
            'service' => 'required'
        ]);

        if ($validate->fails()) {
            return back()->withInput()->withErrors($validate);
        }

        $batch =  Batch::create(
            [
                'name' => auth()->user()->name . '_' . Carbon::now(),
                'status' => 'created',
                'user_id' => auth()->id(),
                'service_id' => $request->service,
                'customer_id' => $request->customer_id
            ]
        );

        $import = new BillRequestImport($batch->id);
        $data = Excel::import($import, $request->file('file'));
        $batch->total = $import->getRowCount();
        $batch->save();

        $first_five_numbers =  BillRequest::where('batch_id', $batch->id)->take(5)->pluck('phone_number');
        $data_count = BillRequest::where('batch_id', $batch->id)->count();    //phone number count in excel file upload
        $last_five_numbers = BillRequest::where('batch_id', $batch->id)->skip($data_count - 5)->take(5)->pluck('phone_number');
        $batch_name = $batch->name;
        $batch_id = $batch->id;

        $amount = DB::table('bill_requests')
            ->join('customers', 'bill_requests.user_id', '=', 'customers.added_user_id')
            ->join('services', 'customers.id', '=', 'services.customer_id')
            ->where('bill_requests.batch_id', $batch_id)
            ->where('services.id', $request->service)
            ->sum('services.amount');

        return view('bill.user.confirm', compact('first_five_numbers', 'data_count', 'last_five_numbers', 'batch_name', 'batch_id', 'amount'));
    }

    public function billRequestPhoneNo(Request $request)
    {
        $validate = validator($request->all(), [
            'phoneNo' => 'required',
            'service' => 'required'
        ]);

        if ($validate->fails()) {
            return back()->withInput()->withErrors($validate);
        }

        $batch =  Batch::create(
            [
                'name' => auth()->user()->name . '_' . Carbon::now(),
                'status' => 'created',
                'user_id' => auth()->id(),
                'service_id' => $request->service,
                'customer_id' => $request->customer_id
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


        $first_five_numbers =  BillRequest::where('batch_id', $batch->id)->take(5)->pluck('phone_number');
        $data_count = BillRequest::where('batch_id', $batch->id)->count();    //phone number count in excel file upload
        $last_five_numbers = BillRequest::where('batch_id', $batch->id)->skip($data_count - 5)->take(5)->pluck('phone_number');
        $batch_name = $batch->name;
        $batch_id = $batch->id;

        $amount = DB::table('bill_requests')
            ->join('customers', 'bill_requests.user_id', '=', 'customers.added_user_id')
            ->join('services', 'customers.id', '=', 'services.customer_id')
            ->where('bill_requests.batch_id', $batch_id)
            ->where('services.id', $request->service)
            ->sum('services.amount');

        return view('bill.user.confirm', compact('first_five_numbers', 'data_count', 'last_five_numbers', 'batch_name', 'batch_id', 'amount'));
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

        return redirect('my-bill-processed');
    }

    public function Processed(Request $request)
    {
        $processedDatas = DB::table('bill_requests')
            ->select(DB::raw('bill_requests.phone_number,bill_requests.description,bill_requests.status,bill_requests.created_at,bill_requests.updated_at, batches.name,customers.name as customer_name, services.name as service_name'))
            ->join('batches', 'bill_requests.batch_id', '=', 'batches.id')
            ->join('services', 'batches.service_id', '=', 'services.id')
            ->join('customers', 'services.customer_id', '=', 'customers.id')
            ->where(function ($query) use ($request) {
                $query->Where('bill_requests.user_id', '=', auth()->user()->id);
            })->orderByDesc('bill_requests.created_at')->limit(100)->get();
        // dd($processedDatas);
        return view('bill.processed', compact('processedDatas'));
    }
}
