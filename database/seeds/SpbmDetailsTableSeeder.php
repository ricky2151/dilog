<?php

use Illuminate\Database\Seeder;
use App\Models\SpbmDetail;

class SpbmDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SpbmDetail::create([
            'spbm_id' => 1,
            'goods_id' => 1,
            'qty' => 10,
            'notes' => "tidak ada catatan",
            'defective_qty' => 0,
            'rack_id' => 1,
        ]);

        SpbmDetail::create([
            'spbm_id' => 1,
            'goods_id' => 2,
            'qty' => 10,
            'notes' => "tidak ada catatan",
            'defective_qty' => 0,
            'rack_id' => 1,
        ]);

        SpbmDetail::create([
            'spbm_id' => 2,
            'goods_id' => 1,
            'qty' => 20,
            'notes' => "tidak ada catatan",
            'defective_qty' => 0,
            'rack_id' => 1,
        ]);

        SpbmDetail::create([
            'spbm_id' => 2,
            'goods_id' => 2,
            'qty' => 50,
            'notes' => "tidak ada catatan",
            'defective_qty' => 0,
            'rack_id' => 1,
        ]);
    }
}
