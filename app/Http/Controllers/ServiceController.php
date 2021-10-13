<?php

namespace App\Http\Controllers;

use App\User;
use App\Service;
use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    public function showForm()
    {
       $role_id =  User::where('id',auth()->user()->id)->first()->role_id;      
        if($role_id != 1){
            $customers = Customer::get();
        }
        return view('services.form',compact('customers'));
    }

    public function create(Request $request)
    {
        $services = Service::where('name',$request->service_name)->where('customer_id',$request->customer)->exists();
        if($services)
        {
           return back()->with('service-name-status','Service name is already exist !');
        }
        else{
            Service::create([
                'name' => $request->service_name,
                'amount' => $request->amount,
                'customer_id' => $request->customer,
                'user_id' => auth()->user()->id
            ]);

            return redirect('fetch-account')->with('service-status','Successfully created service !');
        }
        
    }

    public function addedForm(Request $request, $added_customer_id)
    {      
        $request->session()->put('customer_id',$added_customer_id);
        return view('services.added-form');    
    }

    public function addedService(Request $request)
    {  
        $validate = validator($request->all(),[
            'name' => 'required',
            'amount' => 'required'
        ]);

        if($validate->fails())
        {
            return back()->withInput()->withErrors($validate);
        }
        $services = Service::where('name',$request->name)->where('customer_id',$request->session()->get('customer_id'))->exists();  
        
        if(Service::where('customer_id',$request->session()->get('customer_id'))->exists() && $services)
        {
            return back()->with('service-status','Added service name is already exist with that account !');
        }
        else{
            Service::create([
                'name' => $request->name,
                'amount' => $request->amount,
                'customer_id' => $request->session()->get('customer_id'),
                'user_id' => auth()->user()->id,
            ]);

            return redirect('fetch-account')->with('service-status','Successfully added service with that account !');
        }
    }

    public function editForm(Request $request, $customer_id, $service_id)
    {  
        $request->session()->put('customer_id', $customer_id);
        $request->session()->put('service_id', $service_id);

        if(auth()->user())
        {
            $datas = Service::with('customer')->where('id',$service_id)->where('customer_id',$customer_id)->get();

            return view('services.edit',compact('datas'));
        } 
    }

    public function editService(Request $request)
    {
        $service_id = $request->session()->get('service_id');       

        $validate = validator($request->all(), [
            'service_name' => 'required',
            'amount' => 'required'
        ]);

        if($validate->fails())
        {
            return back()->withInput()->withErrors($validate);
        }

        $services = Service::where('name',$request->service_name)->where('customer_id',$request->customer_id)->exists();

        $data = Service::find($service_id);

        if($services)
        {
            return back()->with('edit-service-status','Service name is already exist with that account !');
        }
        else{

            $data->update([
                'name' => $request->service_name,
                'amount' => $request->amount,
                'user_id' => auth()->user()->id,
                'customer_id' => $request->customer_id
            ]);
            return redirect('fetch-account')->with('service-status','Successfully edited service name !');
        }
    }

    public function deleteService($service_id)
    {
        Service::find($service_id)->delete();
        return back()->with('service-status', 'Successfully deleted service !');
    }
}
