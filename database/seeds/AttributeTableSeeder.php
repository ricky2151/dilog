<?php

use App\Models\Attribute;
use Illuminate\Database\Seeder;

class AttributeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Attribute::create([
            'name' => "Bungkus"
        ]);
        Attribute::create([
            'name' => "Dus"
        ]);
        Attribute::create([
            'name' => "Plastik"
        ]);
        Attribute::create([
            'name' => "Cup"
        ]);
    }
}
