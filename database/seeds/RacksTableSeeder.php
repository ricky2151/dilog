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

        for ($i=0; $i < 100; $i++) {
            Rack::create([
                'name' => "Rack " . $faker->name,
                'warehouse_id' => rand(1,4)
            ]);
        }
    }
}
