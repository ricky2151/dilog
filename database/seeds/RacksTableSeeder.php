<?php

use Illuminate\Database\Seeder;
use App\Models\Rack;
use Faker\Factory as Faker;

class RacksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        Rack::create([
            'name' => "Rack Buku",
            'warehouse_id' => rand(1,4)
        ]);

        Rack::create([
            'name' => "Rack Daging",
            'warehouse_id' => rand(1,4)
        ]);

        Rack::create([
            'name' => "Rack Ayam",
            'warehouse_id' => rand(1,4)
        ]);

        Rack::create([
            'name' => "Rack Sapi",
            'warehouse_id' => rand(1,4)
        ]);
    }
}
