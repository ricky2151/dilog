<?php

use App\Models\GoodsRack;
use Illuminate\Database\Seeder;

class GoodsRacksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1; $i < 104; $i++) {
            GoodsRack::create([
                "goods_id" => $i,
                "rack_id" => rand(1,100),
                "stock" => rand(20,100)
            ]);
        }
    }
}
