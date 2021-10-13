<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Customer;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Customer::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'keyword' => '0a8fe810-1e8e-11eb-8781-e5e7b75f6fa0',
        'token' => Str::random(10),
    ];
});
