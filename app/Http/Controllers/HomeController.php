<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;
use App\Traits\CheckBalance;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    use CheckBalance;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $role = User::where('id', auth()->user()->id)->first()->role_id;

        if ($role != 1) {
            $billTotal = DB::table('bill_requests')
                ->join('batches', 'bill_requests.batch_id', 'batches.id')
                ->join('services', 'batches.service_id', 'services.id')
                ->where('bill_requests.status', 'success')
                ->sum('services.amount');
            $dataTotal = DB::table('data_requests')
                ->join('batches', 'data_requests.batch_id', 'batches.id')
                ->join('packages', 'data_requests.package_id', 'packages.id')
                ->where('data_requests.status', 'success')
                ->sum('packages.price');


            $total = $billTotal + $dataTotal;

            $currMonthBillTotal = DB::table('bill_requests')
                ->join('batches', 'bill_requests.batch_id', 'batches.id')
                ->join('services', 'batches.service_id', 'services.id')
                ->whereYear('bill_requests.created_at', Carbon::now()->year)
                ->whereMonth('bill_requests.created_at', Carbon::now()->month)
                ->where('bill_requests.status', 'success')
                ->sum('services.amount');
            $currMonthDataTotal = DB::table('data_requests')
                ->join('batches', 'data_requests.batch_id', 'batches.id')
                ->join('packages', 'data_requests.package_id', 'packages.id')
                ->where('data_requests.status', 'success')
                ->whereYear('data_requests.created_at', Carbon::now()->year)
                ->whereMonth('data_requests.created_at', Carbon::now()->month)
                ->sum('packages.price');

            $currMonthTotal = $currMonthBillTotal + $currMonthDataTotal;

            $getbillByMPT = DB::select(DB::raw("
                select sum(services.amount) as mpt, bill_requests.operator as operator
                from bill_requests
                inner join batches on batches.id = bill_requests.batch_id
                inner join services on services.id = batches.service_id
                where bill_requests.operator='MPT'
                and bill_requests.status = 'success'
                group by bill_requests.operator;
            "));

            $getbillByTelenor = DB::select(DB::raw("
                select sum(services.amount) as telenor, bill_requests.operator as operator
                from bill_requests
                inner join batches on batches.id = bill_requests.batch_id
                inner join services on services.id = batches.service_id
                where bill_requests.operator='Telenor'
                and bill_requests.status = 'success'
                group by bill_requests.operator;
            "));

            $getbillByOoredoo = DB::select(DB::raw("
                select sum(services.amount) as ooredoo, bill_requests.operator as operator
                from bill_requests
                inner join batches on batches.id = bill_requests.batch_id
                inner join services on services.id = batches.service_id
                where bill_requests.operator='Ooredoo'
                and bill_requests.status = 'success'
                group by bill_requests.operator;
            "));

            $getbillByMyTel = DB::select(DB::raw("
                select sum(services.amount) as mytel, bill_requests.operator as operator
                from bill_requests
                inner join batches on batches.id = bill_requests.batch_id
                inner join services on services.id = batches.service_id
                where bill_requests.operator='MyTel'
                and bill_requests.status = 'success'
                group by bill_requests.operator;
            "));

            if (count($getbillByTelenor) > 0) {
                $billByTelenor = $getbillByTelenor[0]->telenor;
            } else {
                $billByTelenor = 0;
            }
            if (count($getbillByMPT) > 0) {
                $billByMPT = $getbillByMPT[0]->mpt;
            } else {
                $billByMPT = 0;
            }

            if (count($getbillByOoredoo) > 0) {
                $billByOoredoo = $getbillByOoredoo[0]->ooredoo;
            } else {
                $billByOoredoo = 0;
            }

            if (count($getbillByMyTel) > 0) {
                $billByMyTel = $getbillByMyTel[0]->mytel;
            } else {
                $billByMyTel = 0;
            }

            $getdataByMPT = DB::select(DB::raw("
                select sum(packages.price) as mpt , data_requests.operator as operator
                from data_requests
                inner join batches on batches.id = data_requests.batch_id
                inner join packages on packages.id = data_requests.package_id
                where data_requests.operator='MPT'
                and data_requests.status = 'success'
                group by data_requests.operator;
            "));

            $getdataByTelenor = DB::select(DB::raw("
                select sum(packages.price) as telenor , data_requests.operator as operator
                from data_requests
                inner join batches on batches.id = data_requests.batch_id
                inner join packages on packages.id = data_requests.package_id
                where data_requests.operator='Telenor'
                and data_requests.status = 'success'
                group by data_requests.operator;
            "));

            $getdataByOoredoo = DB::select(DB::raw("
                select sum(packages.price) as ooredoo , data_requests.operator as operator
                from data_requests
                inner join batches on batches.id = data_requests.batch_id
                inner join packages on packages.id = data_requests.package_id
                where data_requests.operator='Ooredoo'
                and data_requests.status = 'success'
                group by data_requests.operator;
            "));

            $getdataByMyTel = DB::select(DB::raw("
                select sum(packages.price) as mytel , data_requests.operator as operator
                from data_requests
                inner join batches on batches.id = data_requests.batch_id
                inner join packages on packages.id = data_requests.package_id
                where data_requests.operator='MyTel'
                and data_requests.status = 'success'
                group by data_requests.operator;
            "));

            if (count($getdataByTelenor) > 0) {
                $dataByTelenor = $getdataByTelenor[0]->telenor;
            } else {
                $dataByTelenor = 0;
            }
            if (count($getdataByMPT) > 0) {
                $dataByMPT = $getdataByMPT[0]->mpt;
            } else {
                $dataByMPT = 0;
            }

            if (count($getdataByOoredoo) > 0) {
                $dataByOoredoo = $getdataByOoredoo[0]->ooredoo;
            } else {
                $dataByOoredoo = 0;
            }

            if (count($getdataByMyTel) > 0) {
                $dataByMyTel = $getdataByMyTel[0]->mytel;
            } else {
                $dataByMyTel = 0;
            }

            $packageByPrice = DB::select(DB::raw("
                select sum(packages.price) as price, packages.volume
                from packages
                inner join data_requests on data_requests.package_id = packages.id
                where MONTH(data_requests.created_at)=MONTH(now())
                and YEAR(data_requests.created_at)=YEAR(now())
                and data_requests.status = 'success'
                group by packages.volume;
            "));

            $billByPrice = DB::select(DB::raw("
                select sum(services.amount) as price, services.name as service
                from
                services
                inner join batches on batches.service_id = services.id
                inner join bill_requests on bill_requests.batch_id = batches.id
                where MONTH(bill_requests.created_at)=MONTH(now())
                and YEAR(bill_requests.created_at)=YEAR(now())
                and bill_requests.status = 'success'
                group by services.name;
            "));

            $billTotalCurrentYear = DB::select(DB::raw("
                select month(bill_requests.created_at) as month, sum(services.amount) as price
                from services
                inner join batches on batches.service_id = services.id
                inner join bill_requests on bill_requests.batch_id = batches.id
                where YEAR(bill_requests.created_at)=YEAR(now())
                and bill_requests.status = 'success'
                group by month(bill_requests.created_at);
            "));

            $dataTotalCurrentYear = DB::select(DB::raw("
                select month(data_requests.created_at) as month, sum(packages.price) as price
                from packages
                inner join data_requests on data_requests.package_id = packages.id
                where YEAR(data_requests.created_at)=YEAR(now())
                and data_requests.status = 'success'
                group by month(data_requests.created_at);
            "));

            $totalCurrentYear = DB::select(DB::raw("
                SELECT t.month, SUM(t.price) AS total
                FROM (
                select month(data_requests.created_at) as month, sum(packages.price) as price
                from packages
                inner join data_requests on data_requests.package_id = packages.id
                where YEAR(data_requests.created_at)=YEAR(now())
                and data_requests.status = 'success'
                group by month(data_requests.created_at)
                UNION ALL
                select month(bill_requests.created_at) as month, sum(services.amount) as price
                from services
                inner join batches on batches.service_id = services.id
                inner join bill_requests on bill_requests.batch_id = batches.id
                where YEAR(bill_requests.created_at)=YEAR(now())
                and bill_requests.status = 'success'
                group by month(bill_requests.created_at)
                ) t
                group by t.month;
            "));

            $usedYears = DB::select(DB::raw("
                    SELECT distinct year(created_at) as year FROM batches 
                    order by year(created_at) asc;
            "));

            return view('home', compact(
                'currMonthBillTotal',
                'currMonthDataTotal',
                'total',
                'currMonthTotal',
                'billByMPT',
                'billByTelenor',
                'billByOoredoo',
                'billByMyTel',
                'dataByMPT',
                'dataByTelenor',
                'dataByOoredoo',
                'dataByMyTel',
                'billByPrice',
                'packageByPrice',
                'billTotalCurrentYear',
                'dataTotalCurrentYear',
                'totalCurrentYear',
                'usedYears'
            ));
        } else {

            $id = auth()->id();

            $balance = $this->balance();

            $billTotalByUser = DB::table('bill_requests')
                ->join('batches', 'bill_requests.batch_id', 'batches.id')
                ->join('services', 'batches.service_id', 'services.id')
                ->where('bill_requests.user_id', auth()->id())
                ->where('bill_requests.status', 'success')
                ->sum('services.amount');



            $dataTotalByUser = DB::table('data_requests')
                ->join('batches', 'data_requests.batch_id', 'batches.id')
                ->join('packages', 'data_requests.package_id', 'packages.id')
                ->where('data_requests.user_id', auth()->id())
                ->where('data_requests.status', 'success')
                ->sum('packages.price');

            $total = $billTotalByUser + $dataTotalByUser;

            $currMonthBillTotalByUser = DB::table('bill_requests')
                ->join('batches', 'bill_requests.batch_id', 'batches.id')
                ->join('services', 'batches.service_id', 'services.id')
                ->where('bill_requests.user_id', auth()->id())
                ->where('bill_requests.status', 'success')
                ->whereMonth('bill_requests.created_at', Carbon::now()->month)
                ->whereYear('bill_requests.created_at', Carbon::now()->year)
                ->sum('services.amount');


            $currMonthDataTotalByUser = DB::table('data_requests')
                ->join('batches', 'data_requests.batch_id', 'batches.id')
                ->join('packages', 'data_requests.package_id', 'packages.id')
                ->where('data_requests.user_id', auth()->id())
                ->where('data_requests.status', 'success')
                ->whereMonth('data_requests.created_at', Carbon::now()->month)
                ->whereYear('data_requests.created_at', Carbon::now()->year)
                ->sum('packages.price');


            $currMonthTotal = $currMonthBillTotalByUser + $currMonthDataTotalByUser;

            $getbillByMPT = DB::select(DB::raw("
            select sum(services.amount) as mpt, bill_requests.operator as operator
            from bill_requests
            inner join batches on batches.id = bill_requests.batch_id
            inner join services on services.id = batches.service_id
            where bill_requests.operator = 'MPT'
            and bill_requests.status = 'success'
            and bill_requests.user_id = $id
            group by bill_requests.operator;
        "));

            $getbillByTelenor = DB::select(DB::raw("
            select sum(services.amount) as telenor, bill_requests.operator as operator
            from bill_requests
            inner join batches on batches.id = bill_requests.batch_id
            inner join services on services.id = batches.service_id
            where bill_requests.operator='Telenor'
            and bill_requests.status = 'success'
            and bill_requests.user_id = $id
            group by bill_requests.operator;
        "));

            $getbillByOoredoo = DB::select(DB::raw("
            select sum(services.amount) as ooredoo, bill_requests.operator as operator
            from bill_requests
            inner join batches on batches.id = bill_requests.batch_id
            inner join services on services.id = batches.service_id
            where bill_requests.operator='Ooredoo'
            and bill_requests.status = 'success'
            and bill_requests.user_id = $id
            group by bill_requests.operator;
        "));

            $getbillByMyTel = DB::select(DB::raw("
            select sum(services.amount) as mytel, bill_requests.operator as operator
            from bill_requests
            inner join batches on batches.id = bill_requests.batch_id
            inner join services on services.id = batches.service_id
            where bill_requests.operator='MyTel'
            and bill_requests.status = 'success'
            and bill_requests.user_id = $id
            group by bill_requests.operator;
        "));
            if (count($getbillByTelenor) > 0) {
                $billByTelenor = $getbillByTelenor[0]->telenor;
            } else {
                $billByTelenor = 0;
            }

            if (count($getbillByMPT) > 0) {
                $billByMPT = $getbillByMPT[0]->mpt;
            } else {
                $billByMPT = 0;
            }

            if (count($getbillByOoredoo) > 0) {
                $billByOoredoo = $getbillByOoredoo[0]->ooredoo;
            } else {
                $billByOoredoo = 0;
            }

            if (count($getbillByMyTel) > 0) {
                $billByMyTel = $getbillByMyTel[0]->mytel;
            } else {
                $billByMyTel = 0;
            }

            $getdataByMPT = DB::select(DB::raw("
            select sum(packages.price) as mpt , data_requests.operator as operator
            from data_requests
            inner join batches on batches.id = data_requests.batch_id
            inner join packages on packages.id = data_requests.package_id
            where data_requests.operator='MPT'
            and data_requests.status = 'success'
            and data_requests.user_id = $id
            group by data_requests.operator;
        "));

            $getdataByTelenor = DB::select(DB::raw("
            select sum(packages.price) as telenor , data_requests.operator as operator
            from data_requests
            inner join batches on batches.id = data_requests.batch_id
            inner join packages on packages.id = data_requests.package_id
            where data_requests.operator='Telenor'
            and data_requests.status = 'success'
            and data_requests.user_id = $id
            group by data_requests.operator;
        "));

            $getdataByOoredoo = DB::select(DB::raw("
            select sum(packages.price) as ooredoo , data_requests.operator as operator
            from data_requests
            inner join batches on batches.id = data_requests.batch_id
            inner join packages on packages.id = data_requests.package_id
            where data_requests.operator='Ooredoo'
            and data_requests.status = 'success'
            and data_requests.user_id = $id
            group by data_requests.operator;
        "));

            $getdataByMyTel = DB::select(DB::raw("
            select sum(packages.price) as mytel , data_requests.operator as operator
            from data_requests
            inner join batches on batches.id = data_requests.batch_id
            inner join packages on packages.id = data_requests.package_id
            where data_requests.operator='MyTel'
            and data_requests.status = 'success'
            and data_requests.user_id = $id
            group by data_requests.operator;
        "));

            if (count($getdataByTelenor) > 0) {
                $dataByTelenor = $getdataByTelenor[0]->telenor;
            } else {
                $dataByTelenor = 0;
            }

            if (count($getdataByMPT) > 0) {
                $dataByMPT = $getdataByMPT[0]->mpt;
            } else {
                $dataByMPT = 0;
            }

            if (count($getdataByOoredoo) > 0) {
                $dataByOoredoo = $getdataByOoredoo[0]->ooredoo;
            } else {
                $dataByOoredoo = 0;
            }

            if (count($getdataByMyTel) > 0) {
                $dataByMyTel = $getdataByMyTel[0]->mytel;
            } else {
                $dataByMyTel = 0;
            }

            $packageByPrice = DB::select(DB::raw("
                select sum(packages.price) as price, packages.volume
                from
                packages
                
                inner join data_requests on data_requests.package_id = packages.id
                where MONTH(data_requests.created_at)=MONTH(now())
                and YEAR(data_requests.created_at)=YEAR(now())
                and data_requests.status = 'success'
                and data_requests.user_id = $id
                group by packages.volume;
            "));


            $billByPrice = DB::select(DB::raw("
                select sum(services.amount) as price, services.name as service
                from
                services
                inner join batches on batches.service_id = services.id
                inner join bill_requests on bill_requests.batch_id = batches.id
                where MONTH(bill_requests.created_at)=MONTH(now())
                and YEAR(bill_requests.created_at)=YEAR(now())
                and bill_requests.status = 'success'
                and bill_requests.user_id = $id
                group by services.name;
            "));

            // dd($billByPrice);

            $billTotalCurrentYear = DB::select(DB::raw("
                select month(bill_requests.created_at) as month, sum(services.amount) as price
                from services
                inner join batches on batches.service_id = services.id
                inner join bill_requests on bill_requests.batch_id = batches.id
                where YEAR(bill_requests.created_at)=YEAR(now())
                and bill_requests.status = 'success'
                and bill_requests.user_id = $id
                group by month(bill_requests.created_at);
            "));

            $dataTotalCurrentYear = DB::select(DB::raw("
                select month(data_requests.created_at) as month, sum(packages.price) as price
                from packages
                inner join data_requests on data_requests.package_id = packages.id
                where YEAR(data_requests.created_at)=YEAR(now())
                and data_requests.status = 'success'
                and data_requests.user_id = $id
                group by month(data_requests.created_at);
            "));

            $totalCurrentYear = DB::select(DB::raw("
                SELECT t.month, SUM(t.price) AS total
                FROM (
                select month(data_requests.created_at) as month, sum(packages.price) as price
                from packages
                inner join data_requests on data_requests.package_id = packages.id
                where YEAR(data_requests.created_at)=YEAR(now())
                and data_requests.status = 'success'
                and data_requests.user_id = $id
                group by month(data_requests.created_at)
                UNION ALL
                select month(bill_requests.created_at) as month, sum(services.amount) as price
                from services
                inner join batches on batches.service_id = services.id
                inner join bill_requests on bill_requests.batch_id = batches.id
                where YEAR(bill_requests.created_at)=YEAR(now())
                and bill_requests.status = 'success'
                and bill_requests.user_id = $id
                group by month(bill_requests.created_at)
                ) t
                group by t.month;
            "));

            $usedYears = DB::select(DB::raw("
                    SELECT distinct year(created_at) as year FROM batches 
                    where user_id = $id order by year(created_at) asc;
            "));

            return view(
                'myHome',
                compact(
                    'billTotalByUser',
                    'dataTotalByUser',
                    'currMonthTotal',
                    'total',
                    'currMonthBillTotalByUser',
                    'currMonthDataTotalByUser',
                    'billByMPT',
                    'billByTelenor',
                    'billByOoredoo',
                    'billByMyTel',
                    'dataByMPT',
                    'dataByTelenor',
                    'dataByOoredoo',
                    'dataByMyTel',
                    'billByPrice',
                    'packageByPrice',
                    'balance',
                    'billTotalCurrentYear',
                    'dataTotalCurrentYear',
                    'totalCurrentYear',
                    'usedYears'
                )
            );
        }
    }

    public function ysearch(Request $request)
    {
        $role = User::where('id', auth()->user()->id)->first()->role_id;
        if ($role != 1) {
            //admin
            $sbillTotalCurrentYear = DB::select(DB::raw("
                select month(bill_requests.created_at) as month, sum(services.amount) as price
                from services
                inner join batches on batches.service_id = services.id
                inner join bill_requests on bill_requests.batch_id = batches.id
                where YEAR(bill_requests.created_at)='" . $request->year . "'
                and bill_requests.status = 'success'
                group by month(bill_requests.created_at);
            "));

            $sdataTotalCurrentYear = DB::select(DB::raw("
                select month(data_requests.created_at) as month, sum(packages.price) as price
                from packages
                inner join data_requests on data_requests.package_id = packages.id
                where YEAR(data_requests.created_at)='" . $request->year . "'
                and data_requests.status = 'success'
                group by month(data_requests.created_at);
            "));

            $stotalCurrentYear = DB::select(DB::raw("
                SELECT t.month, SUM(t.price) AS total
                FROM (
                select month(data_requests.created_at) as month, sum(packages.price) as price
                from packages
                inner join data_requests on data_requests.package_id = packages.id
                where YEAR(data_requests.created_at)='" . $request->year . "'
                and data_requests.status = 'success'
                group by month(data_requests.created_at)
                UNION ALL
                select month(bill_requests.created_at) as month, sum(services.amount) as price
                from services
                inner join batches on batches.service_id = services.id
                inner join bill_requests on bill_requests.batch_id = batches.id
                where YEAR(bill_requests.created_at)='" . $request->year . "'
                and bill_requests.status = 'success'
                group by month(bill_requests.created_at)
                ) t
                group by t.month;
            "));

            return [$sbillTotalCurrentYear, $sdataTotalCurrentYear, $stotalCurrentYear];
        } else {

            $id = auth()->id();
            $sbillTotalCurrentYear = DB::select(DB::raw("
                select month(bill_requests.created_at) as month, sum(services.amount) as price
                from services
                inner join batches on batches.service_id = services.id
                inner join bill_requests on bill_requests.batch_id = batches.id
                where YEAR(bill_requests.created_at)='" . $request->year . "'
                and bill_requests.status = 'success'
                and bill_requests.user_id = $id
                group by month(bill_requests.created_at);
            "));

            $sdataTotalCurrentYear = DB::select(DB::raw("
                select month(data_requests.created_at) as month, sum(packages.price) as price
                from packages
                inner join data_requests on data_requests.package_id = packages.id
                where YEAR(data_requests.created_at)='" . $request->year . "'
                and data_requests.status = 'success'
                and data_requests.user_id = $id
                group by month(data_requests.created_at);
            "));

            $stotalCurrentYear = DB::select(DB::raw("
                SELECT t.month, SUM(t.price) AS total
                FROM (
                select month(data_requests.created_at) as month, sum(packages.price) as price
                from packages
                inner join data_requests on data_requests.package_id = packages.id
                where YEAR(data_requests.created_at)='" . $request->year . "'
                and data_requests.status = 'success'
                and data_requests.user_id = $id
                group by month(data_requests.created_at)
                UNION ALL
                select month(bill_requests.created_at) as month, sum(services.amount) as price
                from services
                inner join batches on batches.service_id = services.id
                inner join bill_requests on bill_requests.batch_id = batches.id
                where YEAR(bill_requests.created_at)='" . $request->year . "'
                and bill_requests.status = 'success'
                and bill_requests.user_id = $id
                group by month(bill_requests.created_at)
                ) t
                group by t.month;
            "));

            return [$sbillTotalCurrentYear, $sdataTotalCurrentYear, $stotalCurrentYear];
        }
    }
}
