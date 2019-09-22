<?php

use Illuminate\Database\Seeder;
use App\Models\PurchaseRequestDetail;
use Faker\Factory as Faker;

class PurchaseRequestDetailsTableSeeder extends Seeder
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
            PurchaseRequestDetail::create([
                'purchase_request_id' => $i,
                'goods_id' => rand(1,4),
                'qty' => rand(1, 40),
                'notes' => $faker->sentence(10),
                'pricelist_id' => rand(1,4),
                'price' => rand(1000,10000),
                'supplier_id' => rand(1,4),
                'is_created_as_po' => rand(0,1)
            ]);
        }
    }
}
