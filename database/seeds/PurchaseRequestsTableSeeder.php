<?php

use Illuminate\Database\Seeder;
use App\Models\PurchaseRequest;
use Faker\Factory as Faker;

class PurchaseRequestsTableSeeder extends Seeder
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
            PurchaseRequest::create([
                'code' => "PR" . ($i+1),
                'status' => rand(0,1),
                'created_by_user_id' => rand(1,4),
                'periode_id' => ($i+1)
            ]);
        }
    }
}
