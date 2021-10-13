<?php

namespace App\Traits;

use App\Customer;
use Illuminate\Support\Facades\Http;

trait CheckBalance
{
    public function balance()
    {
        $data = Customer::where('added_user_id', auth()->id())->get();

        $response = Http::withOptions(['allow_redirects' => false])->timeout(30)
            ->withToken($data[0]->token)
            ->post(
                config('settings.bp_gate.balance'),
                [
                    'keyword' => $data[0]->keyword,
                    'email' => $data[0]->user->email
                ]
            );

        $result = json_decode($response);

        if ($result != '') {
            while (property_exists($result, 'status')) {
                $response = [
                    'mpt' => '0',
                    'ooredoo' => '0',
                    'telenor' => '0',
                    'mytel' => '0'
                ];

                return $response;
            }

            return $response;
        } else {
            $response = [
                'mpt' => '0',
                'ooredoo' => '0',
                'telenor' => '0',
                'mytel' => '0'
            ];

            return $response;
        }
    }
}
