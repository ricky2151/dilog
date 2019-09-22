<?php

use Illuminate\Database\Seeder;
use App\Models\MaterialRequestDetail;
use Faker\Factory as Faker;

class MaterialRequestDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        
        for ($i=1; $i < 100; $i++) {
            for ($j=0; $j < 10; $j++) {
                MaterialRequestDetail::create([
                    "material_request_id" => $i,
                    "goods_id" => rand(1,4),
                    "qty" => rand(10, 100),
                    "total" => 10 * $i,
                    "notes"=> $faker->sentence,
                ]);
            }
        }
    }
}
