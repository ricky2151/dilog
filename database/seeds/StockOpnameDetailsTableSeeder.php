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
        StockOpname::create([
            'stock_opname_id ' => 1,
            'name' => "Susu",
            'new_stock' => 1,
            'notes ' => "terindikasi kecurangan"
        ]);

        StockOpname::create([
            'stock_opname_id ' => 2,
            'name' => "Susu2",
            'new_stock' => 2,
            'notes ' => "terindikasi kecurangan"
        ]);

        StockOpname::create([
            'stock_opname_id ' => 3,
            'name' => "Susu",
            'new_stock' => 3,
            'notes ' => "terindikasi kecurangan"
        ]);

        StockOpname::create([
            'stock_opname_id ' => 4,
            'name' => "Susu",
            'new_stock' => 4,
            'notes ' => "terindikasi kecurangan"
        ]);
    }
}
