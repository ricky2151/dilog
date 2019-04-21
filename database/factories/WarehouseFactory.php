<?php

use Faker\Generator as Faker;
use App\Models\Warehouse;
use Illuminate\Support\Str;

$factory->define(Warehouse::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'address' => $faker->address,
        'lat' => $faker->text(20),
        'lng' => $faker->text(20),
        'telp' => $faker->numerify('+62### #### ####'),
        'email' => $faker->email,
        'pic' => $faker->text(20)
    ];
});
