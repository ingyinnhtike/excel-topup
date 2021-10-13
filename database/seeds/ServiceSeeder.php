<?php

use App\Customer;
use App\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $data = [
            [
                'name' => 'Test 1',
                'amount' => 1,
                'customer_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Test',
                'amount' => 500,
                'customer_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('services')->insertOrIgnore($data);
    }
}
