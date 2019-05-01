<?php

use App\Models\Goods;
use Illuminate\Database\Seeder;

class GoodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Goods::create([
            'uuid' => uniqid(),
            'name' => "Buku avenger:end game",
            'code' => "11011",
            'desc' => "Buku special edition",
            'margin' => 1,
            'value' => 1,
            'status' => 1,
            'last_buy_pricelist' => 1,
            'barcode_master' => "XXID190",
            'thumbnail' => "thumbnail/image.jpg",
            'avgprice_status' => true,
            'user_id' => 1,
            'tax' => 5,
            'unit_id' => 1,
            'cogs_id' => 1
        ]);

        Goods::create([
            'uuid' => uniqid(),
            'name' => "Buku avenger:age of ultron",
            'code' => "11011",
            'desc' => "Buku special edition",
            'margin' => 1,
            'value' => 1,
            'status' => 1,
            'last_buy_pricelist' => 1,
            'barcode_master' => "XXID190",
            'thumbnail' => "thumbnail/image.jpg",
            'avgprice_status' => true,
            'user_id' => 1,
            'tax' => 5,
            'unit_id' => 1,
            'cogs_id' => 1
        ]);

        Goods::create([
            'uuid' => uniqid(),
            'name' => "Buku avenger",
            'code' => "11011",
            'desc' => "Buku special edition",
            'margin' => 1,
            'value' => 1,
            'status' => 1,
            'last_buy_pricelist' => 1,
            'barcode_master' => "XXID190",
            'thumbnail' => "thumbnail/image.jpg",
            'avgprice_status' => true,
            'user_id' => 1,
            'tax' => 5,
            'unit_id' => 1,
            'cogs_id' => 1
        ]);

        Goods::create([
            'uuid' => uniqid(),
            'name' => "Buku avenger:infinity war",
            'code' => "11011",
            'desc' => "Buku special edition",
            'margin' => 1,
            'value' => 1,
            'status' => 1,
            'last_buy_pricelist' => 1,
            'barcode_master' => "XXID190",
            'thumbnail' => "thumbnail/image.jpg",
            'avgprice_status' => true,
            'user_id' => 1,
            'tax' => 5,
            'unit_id' => 1,
            'cogs_id' => 1
        ]);
    }
}
