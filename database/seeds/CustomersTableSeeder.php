<?php

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Customer::create([
            "name" => "Customer 1",
            "no_hp" => "089111111",
            "address" => "Address 1"
        ]);

        Customer::create([
            "name" => "Customer 2",
            "no_hp" => "089111112",
            "address" => "Address 2"
        ]);

        Customer::create([
            "name" => "Customer 3",
            "no_hp" => "089111113",
            "address" => "Address 3"
        ]);

        Customer::create([
            "name" => "Customer 4",
            "no_hp" => "089111114",
            "address" => "Address 4"
        ]);
    }
}
