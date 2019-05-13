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
        DB::table('attribute_goods')->insert([
            'goods_id' => 1,
            'attribute_id' => 1,
            'value' => 10,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('attribute_goods')->insert([
            'goods_id' => 1,
            'attribute_id' => 2,
            'value' => 10,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('attribute_goods')->insert([
            'goods_id' => 2,
            'attribute_id' => 1,
            'value' => 10,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('attribute_goods')->insert([
            'goods_id' => 2,
            'attribute_id' => 2,
            'value' => 10,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
