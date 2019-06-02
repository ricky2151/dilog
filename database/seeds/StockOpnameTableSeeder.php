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
            'user_id' => 1,
            'notes' => "terindikasi kecurangan"
        ]);

        StockOpname::create([
            'periode' => "2019-10-06",
            'user_id' => 1,
            'notes' => "terindikasi kecurangan"
        ]);

        StockOpname::create([
            'periode' => "2019-10-07",
            'user_id' => 1,
            'notes' => "terindikasi kecurangan"
        ]);

        StockOpname::create([
            'periode' => "2019-10-08",
            'user_id' => 1,
            'notes' => "terindikasi kecurangan"
        ]);
    }
}
