<?php

use Illuminate\Database\Seeder;
use App\Models\Spbm;

class SpbmsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Spbm::create([
            'purchase_order_id' => 1,
            'arrival_date' => now(),
            'delivery_order_no' => "SPBM 1",
            'catatan' => "tidak ada catatan",
            'warehouse_id' => 1,
            'status' => 0,
        ]);

        Spbm::create([
            'purchase_order_id' => 1,
            'arrival_date' => now(),
            'delivery_order_no' => "SPBM 2",
            'catatan' => "tidak ada catatan",
            'warehouse_id' => 1,
            'status' => 0,
        ]);
    }
}
