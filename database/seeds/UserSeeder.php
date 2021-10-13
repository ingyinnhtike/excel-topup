<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {       

        $admins = [
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('ZI@dmIn'),
                'role_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        $supers = [
            [
                'name' => 'super',
                'email' => 'super@gmail.com',
                'password' => Hash::make('ZI@dmIn'),
                'role_id' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ]

        ];

        $users = [
            [
                'name' => 'Blue Planet',
                'email' => 'bp@gmail.com',
                'password' => Hash::make('ZI@dmIn'),
                'role_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($admins as $admin) {
            $results = User::create($admin);
            $results->assignRole('admin');
        }

        foreach ($supers as $super)
        {
            $super = User::create($super);
            $super->assignRole('super-admin');
        }

        foreach ($users as $user) {
            $data = User::create($user);
            $data->assignRole('user');
        }

    }
}
