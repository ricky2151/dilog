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
    }
}
