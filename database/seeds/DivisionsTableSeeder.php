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
            "status" => true,
            "pic_user_id" => 1
        ]);

        Division::create([
            "name" => "Logistic",
            "status" => true,
            "pic_user_id" => 2
        ]);

        Division::create([
            "name" => "IT",
            "status" => true,
            "pic_user_id" => 1
        ]);

        Division::create([
            "name" => "Management",
            "status" => true,
            "pic_user_id" => 1
        ]);
    }
}
