<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeGoodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1; $i < 104; $i++) {
            DB::table('attribute_goods')->insert([
                'goods_id' => $i,
                'attribute_id' => rand(1,4),
                'value' => 10,
            ]);
        }
    }
}
