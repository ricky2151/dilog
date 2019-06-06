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
        GoodsRack::create([
            "goods_id" => 1,
            "rack_id" => 1,
            "stock" => 1
        ]);
        GoodsRack::create([
            "goods_id" => 2,
            "rack_id" => 2,
            "stock" => 2
        ]);
        GoodsRack::create([
            "goods_id" => 3,
            "rack_id" => 3,
            "stock" => 3
        ]);
        GoodsRack::create([
            "goods_id" => 4,
            "rack_id" => 4,
            "stock" => 4
        ]);
    }
}
