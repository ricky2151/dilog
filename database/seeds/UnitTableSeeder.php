<?php

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Unit::create([
            'name' => "unit 1"
        ]);
        Unit::create([
            'name' => "unit 2"
        ]);
        Unit::create([
            'name' => "unit 3"
        ]);
        Unit::create([
            'name' => "unit 4"
        ]);
        Unit::create([
            'name' => "unit 5"
        ]);
        Unit::create([
            'name' => "unit 6"
        ]);
        Unit::create([
            'name' => "unit 7"
        ]);
        Unit::create([
            'name' => "unit 8"
        ]);
        Unit::create([
            'name' => "unit 9"
        ]);
        Unit::create([
            'name' => "unit 10"
        ]);
    }
}
