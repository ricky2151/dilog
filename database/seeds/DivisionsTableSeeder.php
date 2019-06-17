<?php

use Illuminate\Database\Seeder;
use App\Models\Division;

class DivisionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Division::create([
            "name" => "Finance",
            "mr_enable" => true
        ]);

        Division::create([
            "name" => "Logistic",
            "mr_enable" => true
        ]);

        Division::create([
            "name" => "IT",
            "mr_enable" => true
        ]);

        Division::create([
            "name" => "Management",
            "mr_enable" => false
        ]);
    }
}
