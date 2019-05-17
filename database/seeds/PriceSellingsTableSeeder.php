<?php

use App\Models\PriceSelling;
use Illuminate\Database\Seeder;

class PriceSellingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PriceSelling::create([
            "goods_rack_id" => 1,
            "stock_cut_off" => 1,
            "category_price_selling_id" => 1,
            "price" => 1,
            "discount" => 1,
            "free" => 1
        ]);

        PriceSelling::create([
            "goods_rack_id" => 2,
            "stock_cut_off" => 2,
            "category_price_selling_id" => 2,
            "price" => 2,
            "discount" => 2,
            "free" => 1
        ]);

        PriceSelling::create([
            "goods_rack_id" => 3,
            "stock_cut_off" => 3,
            "category_price_selling_id" => 3,
            "price" => 3,
            "discount" => 3,
            "free" => 0
        ]);

        PriceSelling::create([
            "goods_rack_id" => 4,
            "stock_cut_off" => 4,
            "category_price_selling_id" => 4,
            "price" => 4,
            "discount" => 4,
            "free" => 0
        ]);
    }
}
