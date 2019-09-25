<?php

use App\Models\Type;
use Illuminate\Database\Seeder;

class TypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Type::create([
            'name' => "Type 1"
        ]);
        Type::create([
            'name' => "Type 2"
        ]);
        Type::create([
            'name' => "Type 3"
        ]);
        Type::create([
            'name' => "Type 4"
        ]);
        Type::create([
            'name' => "Type 5"
        ]);
        Type::create([
            'name' => "Type 6"
        ]);
        Type::create([
            'name' => "Type 7"
        ]);
        Type::create([
            'name' => "Type 8"
        ]);
        Type::create([
            'name' => "Type 9"
        ]);
        Type::create([
            'name' => "Type 10"
        ]);
    }
}
