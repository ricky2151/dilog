<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryGoodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('category_goods')->insert([
            'goods_id' => 1,
            'category_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('category_goods')->insert([
            'goods_id' => 1,
            'category_id' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('category_goods')->insert([
            'goods_id' => 2,
            'category_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('category_goods')->insert([
            'goods_id' => 2,
            'category_id' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
