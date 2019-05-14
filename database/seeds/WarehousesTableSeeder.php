<?php

use Illuminate\Database\Seeder;
use App\Models\Warehouse;
use Faker\Factory as Faker;

class WarehousesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $faker = Faker::create();
        Warehouse::create([
            'name' => "Bakpia Ku",
            'address' => $faker->address,
            'lat' => $faker->text(20),
            'lng' => $faker->text(20),
            'telp' => $faker->numerify('+62###########'),
            'email' => $faker->email,
            'pic' => $faker->name
        ]);
        Warehouse::create([
            'name' => "Bakpia Tugu",
            'address' => $faker->address,
            'lat' => $faker->text(20),
            'lng' => $faker->text(20),
            'telp' => $faker->numerify('+62###########'),
            'email' => $faker->email,
            'pic' => $faker->name
        ]);
        Warehouse::create([
            'name' => "Bakpia Kukus",
            'address' => $faker->address,
            'lat' => $faker->text(20),
            'lng' => $faker->text(20),
            'telp' => $faker->numerify('+62###########'),
            'email' => $faker->email,
            'pic' => $faker->name
        ]);
        Warehouse::create([
            'name' => "Bakpia Pathok",
            'address' => $faker->address,
            'lat' => $faker->text(20),
            'lng' => $faker->text(20),
            'telp' => $faker->numerify('+62 ### #### ####'),
            'email' => $faker->email,
            'pic' => $faker->name
        ]);
    }
}
