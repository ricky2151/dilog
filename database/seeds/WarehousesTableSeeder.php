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

        $warehouses = ['Bakpia Ku', 'Bakpia Tugu', 'Bakpia Kukus', 'Bakpia Pathuk 25', 'Bakpia Pathuk 75', 'Bakpia Djava', 'Bakpia BW', 'Cakekinian', 'Hudson Cake', 'Cake Masa Depan'];

        $faker = Faker::create();
        foreach ($warehouses as $key => $warehouse) {
            Warehouse::create([
                'name' => $warehouse,
                'address' => $faker->address,
                'lat' => $faker->text(20),
                'lng' => $faker->text(20),
                'telp' => $faker->numerify('+62###########'),
                'email' => $faker->email,
                'pic' => $faker->name
            ]);
        }
    }
}
