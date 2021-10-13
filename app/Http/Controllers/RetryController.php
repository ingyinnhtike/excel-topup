<?php

namespace App\Http\Controllers;

use App\BillRequest;
use App\DataRequest;
use App\Jobs\RetryProcess;
use App\Helpers\PhoneParse;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Jobs\ProcessBillTopup;
use App\Jobs\ProcessDataTopup;
use Illuminate\Support\Facades\DB;
use App\User;

class RetryController extends Controller
{
    public function retryBillRequest(Request $request)
    {
        // update retry count in batches table

        $getRetryCount = DB::select(DB::raw("select retry from batches where id = $request->batch_id"));
        $sumRetry = $getRetryCount[0]->retry + 1;
        DB::table('batches')
            ->where('id', $request->batch_id)
            ->update(['retry' => $sumRetry]);

        // end update retry count in batches table
        $array = [];
        foreach ($request->phone as $key => $value) {
            DB::table('bill_requests')
                ->where('batch_id', $request->batch_id)
                ->where('phone_number', $value)
                ->update(['reference_id' => Str::uuid()]);

            array_push($array, $value);
        }

        $processDatas = DB::table('bill_requests')
            ->select(DB::raw('bill_requests.*,customers.keyword,customers.token,services.name as service_name'))
            ->join('batches', 'bill_requests.batch_id', '=', 'batches.id')
            ->join('services', 'batches.service_id', '=', 'services.id')
            ->join('customers', 'services.customer_id', '=', 'customers.id')
            ->where('bill_requests.batch_id', '=', $request->batch_id)
            ->whereIn('bill_requests.phone_number', $array)
            ->where('bill_requests.status', '!=', 'success')
            ->get();
        // dd($processDatas);

        foreach ($processDatas as $data) {
            ProcessBillTopup::dispatch($data->phone_number, $data->reference_id, $data->batch_id, $data->keyword, $data->token, $data->service_name)->delay(now()->addSeconds(5));
        }

        if (User::where('id', auth()->user()->id)->first()->role_id != 1) {
            return redirect('all-batches');
        }
        return redirect('my-batches');
    }

    public function retryDataRequest(Request $request)
    {
        // return $request;
        $getRetryCount = DB::select(DB::raw("select retry from batches where id = $request->batch_id"));
        $sumRetry = $getRetryCount[0]->retry + 1;
        DB::table('batches')
            ->where('id', $request->batch_id)
            ->update(['retry' => $sumRetry]);

        // end update retry count in batches table
        $array = [];
        foreach ($request->phone as $key => $value) {
            DB::table('data_requests')
                ->where('batch_id', $request->batch_id)
                ->where('phone_number', $value)
                ->update(['reference_id' => Str::uuid()]);

            array_push($array, $value);
        }

        $processDatas = DB::table('data_requests')
            ->select(DB::raw('data_requests.*,customers.keyword,customers.token,packages.package_name as package_name,packages.package_code as package_code,services.name as service_name'))
            //            ->join('packages', 'data_requests.package_id', '=', 'packages.id')
            //            ->join('batches', 'data_requests.batch_id', '=', 'batches.id')
            //            ->join('customers', 'batches.customer_id', '=', 'customers.id')
            ->join('batches', 'data_requests.batch_id', '=', 'batches.id')
            ->join('services', 'batches.service_id', '=', 'services.id')
            ->join('customers', 'services.customer_id', '=', 'customers.id')
            ->join('packages', 'packages.id', '=', 'data_requests.package_id')
            ->whereIn('data_requests.phone_number', $array)
            ->where(function ($query) use ($request) {
                $query->Where('data_requests.batch_id', '=', $request->batch_id);
            })->get();

        foreach ($processDatas as $data) {
            ProcessDataTopup::dispatch($data->phone_number, $data->reference_id, $data->batch_id, $data->keyword, $data->token, $data->package_name, $data->package_code, $data->service_name)->delay(now()->addSeconds(5));
        }

        if (User::where('id', auth()->user()->id)->first()->role_id != 1) {
            return redirect('all-batches');
        }
        return redirect('my-batches');
    }
}
