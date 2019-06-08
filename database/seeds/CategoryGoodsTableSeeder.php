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
        for ($i=1; $i < 104; $i++) {
            DB::table('category_goods')->insert([
                'goods_id' => $i,
                'category_id' => rand(1,4),
            ]);
        }
    }
}
