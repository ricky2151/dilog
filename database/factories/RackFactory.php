<?php

use App\Models\Rack;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Rack::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'warehouse_id' => rand(1,4)
    ];
});
