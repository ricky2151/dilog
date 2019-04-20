<?php

use App\Models\Role;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Role::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'desc' => $faker->text
    ];
});
