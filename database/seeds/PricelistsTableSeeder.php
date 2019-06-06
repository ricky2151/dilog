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
        DB::table('pricelists')->insert([
            'goods_id' => 1,
            'supplier_id' => 1,
            'price' => 10000,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('pricelists')->insert([
            'goods_id' => 2,
            'supplier_id' => 2,
            'price' => 20000,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('pricelists')->insert([
            'goods_id' => 3,
            'supplier_id' => 3,
            'price' => 30000,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('pricelists')->insert([
            'goods_id' => 4,
            'supplier_id' => 4,
            'price' => 40000,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
