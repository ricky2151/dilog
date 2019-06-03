<?php

use Illuminate\Database\Seeder;
use App\Models\StockOpnameDetail;

class StockOpnameDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StockOpnameDetail::create([
            'stock_opname_id' => 1,
            'goods_id' => 1,
            'new_stock' => 1,
            'current_stock' => 1,
            'notes' => "terindikasi kecurangan"
        ]);

        StockOpnameDetail::create([
            'stock_opname_id' => 2,
            'goods_id' => 2,
            'new_stock' => 2,
            'current_stock' => 2,
            'notes' => "terindikasi kecurangan"
        ]);

        StockOpnameDetail::create([
            'stock_opname_id' => 3,
            'goods_id' => 3,
            'new_stock' => 3,
            'current_stock' => 3,
            'notes' => "terindikasi kecurangan"
        ]);

        StockOpnameDetail::create([
            'stock_opname_id' => 4,
            'goods_id' => 4,
            'new_stock' => 4,
            'current_stock' => 4,
            'notes' => "terindikasi kecurangan"
        ]);
    }
}
