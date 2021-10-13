<?php

namespace App\Http\Controllers;

use App\User;
use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Helpers\EncryptDecrypt;

class AccountController extends Controller
{
    public function index()
    {
        $datas = DB::select(DB::raw('
            select c.id, c.name,c.phone_number,c.logo, u.email, u.name as usrname, u.updated_at
            from customers c
            inner join users u on u.id = c.added_user_id order by u.name;
        '));

        // dd($datas);

        return view('account.home', compact('datas'));
    }

    public function showForm()
    {
        return view('account.form');
    }

    public function create(Request $request)
    {
        $validate = validator($request->all(), [
            'contact_name' => 'required|max:191|min:6',
            'name' => 'required|max:191|min:2|unique:customers',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'phone_number' => 'required|digits_between:9,12',
            'address' => 'required',
            'file' => 'file|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ], [
            'name.unique:customers' => 'Company name has already been taken.'
        ]);

        if ($validate->fails()) {
            return back()->withInput()->withErrors($validate);
        }


        $user_name = $request->contact_name;
        $merchant_name = $request->name;
        $merchant_phone = $request->phone_number;
        $merchant_address = $request->address;
        $email = $request->email;
        $password = $request->password;

        $encrypted_usrname = EncryptDecrypt::myEncrypt($user_name);
        $encrypted_email = EncryptDecrypt::myEncrypt($email);
        $encrypted_password = EncryptDecrypt::myEncrypt($password);
        $encrypted_merchant_name = EncryptDecrypt::myEncrypt($merchant_name);
        $encrypted_merchant_phone = EncryptDecrypt::myEncrypt($merchant_phone);
        $encrypted_merchant_address = EncryptDecrypt::myEncrypt($merchant_address);


        $data = Customer::where('id', auth()->id())->get();
        $response = Http::withOptions(['allow_redirects' => false])->timeout(60)
            ->withToken($data[0]->token)
            ->post(
                config('settings.bp_gate.registeration'),
                [
                    'name' => $encrypted_merchant_name,
                    'merchant_phone' => $encrypted_merchant_phone,
                    'email' => $encrypted_email,
                    'merchant_address' => $encrypted_merchant_address,
                    'user_name' => $encrypted_usrname,
                    'password' => $encrypted_password,
                    // 'logo' => $file
                ]
            );


        $result = $response;
        // return $result;

        if ($response["status"] == "success") {
            $customer = new Customer();
            $user = new User();

            $user->name = $user_name;
            $user->email = $email;
            $user->password = Hash::make($request->password);
            $user->save();


            if ($request->file()) {

                $filename = time() . '_' . $request->logo->getClientOriginalName();
                $filepath = $request->file('logo')->storeAs('logo', $filename, 'public');
                $logo = '/storage/' . $filepath;
            } else {
                $logo = "";
            }

            $decrypted_uuid = EncryptDecrypt::myDecrypt($result["uuid"]);
            $decrypted_token = EncryptDecrypt::myDecrypt($result["token"]);
            $decode = json_decode($decrypted_token);


            $customer->name = $merchant_name;
            $customer->keyword = $decrypted_uuid;
            $customer->token = $decode->access_token;
            // $customer->token = 'jfalsdjfopherujvnjenvveriegihi4r84u8u38u4923in3r34gkj45yjn65u';
            $customer->phone_number = $merchant_phone;
            $customer->address = $merchant_address;
            $customer->user_id = auth()->id();
            $customer->added_user_id = $user->id;
            $customer->logo = $logo;
            $customer->save();

            // Data
            DB::table('services')->insert([
                'customer_id' => $customer->id,
                'name' => "Data",
                'amount' => 0,
                'created_at' => date("Y-m-d h:i:s"),
                'updated_at' => date("Y-m-d h:i:s")

            ]);

            // Bill
            DB::table('services')->insert([
                'customer_id' => $customer->id,
                'name' => "1000",
                'amount' => 1000,
                'created_at' => date("Y-m-d h:i:s"),
                'updated_at' => date("Y-m-d h:i:s")

            ]);

            DB::table('services')->insert([
                'customer_id' => $customer->id,
                'name' => "2000",
                'amount' => 2000,
                'created_at' => date("Y-m-d h:i:s"),
                'updated_at' => date("Y-m-d h:i:s")

            ]);

            DB::table('services')->insert([
                'customer_id' => $customer->id,
                'name' => "3000",
                'amount' => 3000,
                'created_at' => date("Y-m-d h:i:s"),
                'updated_at' => date("Y-m-d h:i:s")

            ]);

            DB::table('services')->insert([
                'customer_id' => $customer->id,
                'name' => "4000",
                'amount' => 4000,
                'created_at' => date("Y-m-d h:i:s"),
                'updated_at' => date("Y-m-d h:i:s")

            ]);

            DB::table('services')->insert([
                'customer_id' => $customer->id,
                'name' => "5000",
                'amount' => 5000,
                'created_at' => date("Y-m-d h:i:s"),
                'updated_at' => date("Y-m-d h:i:s")

            ]);

            DB::table('services')->insert([
                'customer_id' => $customer->id,
                'name' => "10000",
                'amount' => 10000,
                'created_at' => date("Y-m-d h:i:s"),
                'updated_at' => date("Y-m-d h:i:s")

            ]);

            DB::table('services')->insert([
                'customer_id' => $customer->id,
                'name' => "20000",
                'amount' => 20000,
                'created_at' => date("Y-m-d h:i:s"),
                'updated_at' => date("Y-m-d h:i:s")

            ]);

            DB::table('services')->insert([
                'customer_id' => $customer->id,
                'name' => "30000",
                'amount' => 30000,
                'created_at' => date("Y-m-d h:i:s"),
                'updated_at' => date("Y-m-d h:i:s")

            ]);

            return redirect('fetch-account')->with('account-status', 'Successfully created account !');
        } else {
            return back()->with('api-response', array_values($result["error"])[0]);
        }
    }


    public function edit(Request $request)
    {
        $getuser = DB::select(DB::raw(
            "
                select c.id, c.name,c.phone_number,c.address,c.logo, u.email, u.name as usrname, u.updated_at
                from customers c
                inner join users u on u.id = c.added_user_id 
                where c.id = $request->id;
            "
        ));

        return $getuser;
    }

    public function update(Request $request)
    {

        $validate = validator($request->all(), [
            'username' => 'required|max:191|min:6',
            'email' => 'required|email',
            'phone' => 'required|digits_between:9,12',
            'address' => 'required',
            'file' => 'file|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($validate->fails()) {
            return array_values($validate->errors()->getMessages())[0];
        }

        // portal update
        $data = Customer::where('id', auth()->id())->get();
        $user_name = $request->username;
        $merchant_phone = $request->phone;
        $merchant_address = $request->address;
        $email = $request->email;

        $response = Http::withOptions(['allow_redirects' => false])->timeout(30)
            ->withToken($data[0]->token)
            ->post(
                config('settings.bp_gate.updateuserinfo'),
                [
                    'phone' => $merchant_phone,
                    'address' => $merchant_address,
                    'username' => $user_name,
                    'email' => $email,
                ]
            );

        $result = $response;

        if ($result["status"] == "success") {
            // local update

            if ($request->file()) {
                if ($request->oldlogo != "" && $request->oldlogo == null) {
                    $oldphoto = $request->oldlogo;
                    $oldphotoarray = explode("/", $oldphoto);
                    $oldphotofilename = $oldphotoarray[3];
                    $oldphotofilepath = 'app/public/logo/' . $oldphotofilename;
                    unlink(storage_path($oldphotofilepath));
                }


                $filename = time() . '_' . $request->logo->getClientOriginalName();
                $filepath = $request->file('logo')->storeAs('logo', $filename, 'public');
                $logo = '/storage/' . $filepath;
            } else {
                if ($request->oldlogo != "") {
                    $logo = $request->oldlogo;
                } else {
                    $logo = "";
                }
            }
            $getuser = Customer::where('id', $request->id)->get();

            DB::table('users')
                ->where('id', $getuser[0]->added_user_id)
                ->update(['name' => $request->username, 'updated_at' => date("Y-m-d h:i:s")]);

            DB::table('customers')
                ->where('id', $request->id)
                ->update(['phone_number' => $request->phone, 'address' => $request->address, 'logo' => $logo, 'updated_at' => date("Y-m-d h:i:s")]);

            return "success";
        } else {
            return "fail";
        }
    }
}
