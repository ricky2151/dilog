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
            'uuid' => "Rack Buku",
            'name' => "Rack Buku",
            'code' => "Rack Buku",
            'desc' => "Rack Buku",
            'margin' => "Rack Buku",
            'value' => "Rack Buku",
            'status' => "Rack Buku",
            'last_buy_pricelist' => "Rack Buku",
            'barcode_master' => "Rack Buku",
            'thumbnail' => "Rack Buku",
            'avgprice_status' => "Rack Buku",
            'user_id' => "Rack Buku",
            'tax' => "Rack Buku",
            'unit_id' => "Rack Buku",
            'cogs_id' => "Rack Buku",
            'warehouse_id' => rand(1,4)
        ]);
    }
}
