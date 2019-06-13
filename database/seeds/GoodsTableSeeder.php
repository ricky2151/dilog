<?php

use App\Models\Goods;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class GoodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        Goods::create([
            'name' => "Buku avenger:end game",
            'code' => "11011",
            'desc' => "Buku special edition",
            'margin' => 1,
            'value' => 1,
            'status' => 1,
            'last_buy_pricelist' => 1,
            'barcode_master' => "XXID190",
            'thumbnail' => "goods/default.png",
            'avg_price_status' => true,
            'avg_price' => 100,
            'user_id' => 1,
            'tax' => 5,
            'unit_id' => 1,
            'cogs_id' => 1
        ]);

        Goods::create([
            'name' => "Buku avenger:age of ultron",
            'code' => "11011",
            'desc' => "Buku special edition",
            'margin' => 1,
            'value' => 1,
            'status' => 1,
            'last_buy_pricelist' => 1,
            'barcode_master' => "XXID190",
            'thumbnail' => "goods/default.png",
            'avg_price_status' => true,
            'avg_price' => 100,
            'user_id' => 1,
            'tax' => 5,
            'unit_id' => 1,
            'cogs_id' => 1
        ]);

        Goods::create([
            'name' => "Buku avenger",
            'code' => "11011",
            'desc' => "Buku special edition",
            'margin' => 1,
            'value' => 1,
            'status' => 1,
            'last_buy_pricelist' => 1,
            'barcode_master' => "XXID190",
            'thumbnail' => "goods/default.png",
            'avg_price_status' => true,
            'avg_price' => 100,
            'user_id' => 1,
            'tax' => 5,
            'unit_id' => 1,
            'cogs_id' => 1
        ]);

        Goods::create([
            'name' => "Buku avenger:infinity war",
            'code' => "11011",
            'desc' => "Buku special edition",
            'margin' => 1,
            'value' => 1,
            'status' => 1,
            'last_buy_pricelist' => 1,
            'barcode_master' => "XXID190",
            'thumbnail' => "goods/default.png",
            'avg_price_status' => true,
            'avg_price' => 100,
            'user_id' => 1,
            'tax' => 5,
            'unit_id' => 1,
            'cogs_id' => 1
        ]);

        for ($i=1; $i < 100; $i++) {
            Goods::create([
                'name' => "Buku " . $faker->name,
                'code' => rand(1000, 9000),
                'desc' => "Buku " . $faker->name,
                'margin' => 1,
                'value' => 1,
                'status' => 1,
                'last_buy_pricelist' => rand(10000, 90000),
                'barcode_master' => "XXID" . rand(100,200),
                'thumbnail' => "goods/default.png",
                'avg_price_status' => true,
                'avg_price' => rand(100,200),
                'user_id' => rand(1,10),
                'tax' => 5,
                'unit_id' => rand(1,4),
                'cogs_id' => rand(1,4)
            ]);
        }
    }
}
