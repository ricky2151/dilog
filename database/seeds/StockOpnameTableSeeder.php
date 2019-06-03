<?php

use Illuminate\Database\Seeder;
use App\Models\StockOpname;

class StockOpnameTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StockOpname::create([
            'periode' => "2019-10-05",
            'warehouse_id' => 1,
            'user_id' => 1,
            'notes' => "terindikasi kecurangan"
        ]);

        StockOpname::create([
            'periode' => "2019-10-06",
            'warehouse_id' => 2,
            'user_id' => 2,
            'notes' => "terindikasi kecurangan"
        ]);

        StockOpname::create([
            'periode' => "2019-10-07",
            'warehouse_id' => 3,
            'user_id' => 3,
            'notes' => "terindikasi kecurangan"
        ]);

        StockOpname::create([
            'periode' => "2019-10-08",
            'warehouse_id' => 4,
            'user_id' => 4,
            'notes' => "terindikasi kecurangan"
        ]);
    }
}
