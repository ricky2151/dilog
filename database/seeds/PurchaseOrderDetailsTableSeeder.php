<?php

use Illuminate\Database\Seeder;
use App\Models\PurchaseOrderDetail;
use Faker\Factory as Faker;

class PurchaseOrderDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i=0; $i < 4; $i++) {
            PurchaseOrderDetail::create([
                'purchase_order_id' => $i+1,
                'pricelist_id' => rand(1,4),
                'goods_id' => rand(1,4),
                'qty' => rand(1,100),
                'subtotal' => rand(10000,20000),
                'tax' => rand(1,3),
                'discount_percent' => rand(0,50),
                'discount_rupiah' => rand(1000,4000),
                'is_edited' => rand(0,1)
            ]);
        }
    }
}
