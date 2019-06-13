<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PricelistsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 100; $i++) {
            DB::table('pricelists')->insert([
                'goods_id' => 1,
                'supplier_id' => rand(1,4),
                'price' => rand(10000, 1000000),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
