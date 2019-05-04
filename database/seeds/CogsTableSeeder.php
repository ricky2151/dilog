<?php

use App\Models\Cogs;
use Illuminate\Database\Seeder;

class CogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cogs::create([
            "name" => "Cogs 1",
            "nominal" => 100,
            "type_id" => 1
        ]);
        Cogs::create([
            "name" => "Cogs 2",
            "nominal" => 200,
            "type_id" => 2
        ]);
        Cogs::create([
            "name" => "Cogs 3",
            "nominal" => 300,
            "type_id" => 1
        ]);
        Cogs::create([
            "name" => "Cogs 4",
            "nominal" => 400,
            "type_id" => 4
        ]);
    }
}
