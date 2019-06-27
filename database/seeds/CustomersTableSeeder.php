<?php

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Faker\Factory as Faker;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($i=0; $i < 100; $i++) {
            Customer::create([
                "name" => $faker->name,
                "no_hp" => $faker->phoneNumber,
                "address" => $faker->address
            ]);
        }
    }
}
