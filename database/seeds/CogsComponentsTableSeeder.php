<?php

use App\Models\CogsComponent;
use Illuminate\Database\Seeder;

class CogsComponentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CogsComponent::create([
            "name" => "Cogs Component 1",
            "value" => 100,
            "info" => "Info Cog Component 1",
            "cogs_id" => 1
        ]);
        CogsComponent::create([
            "name" => "Cogs Component 2",
            "value" => 200,
            "info" => "Info Cog Component 2",
            "cogs_id" => 2
        ]);
        CogsComponent::create([
            "name" => "Cogs Component 3",
            "value" => 300,
            "info" => "Info Cog Component 3",
            "cogs_id" => 3
        ]);
        CogsComponent::create([
            "name" => "Cogs Component 4",
            "value" => 400,
            "info" => "Info Cog Component 4",
            "cogs_id" => 4
        ]);
    }
}
