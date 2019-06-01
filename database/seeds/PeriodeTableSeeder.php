<?php

use Illuminate\Database\Seeder;
use App\Models\Periode;
use Carbon\Carbon;

class PeriodeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Periode::create([
            "name" => "Periode 1",
            "from" => Carbon::parse('2000-01-01'),
            "to" => Carbon::parse('2000-03-31')
        ]);

        Periode::create([
            "name" => "Periode 2",
            "from" => Carbon::parse('2000-04-01'),
            "to" => Carbon::parse('2000-06-30')
        ]);

        Periode::create([
            "name" => "Periode 3",
            "from" => Carbon::parse('2000-07-01'),
            "to" => Carbon::parse('2000-09-30')
        ]);

        Periode::create([
            "name" => "Periode 4",
            "from" => Carbon::parse('2000-10-01'),
            "to" => Carbon::parse('2000-12-31')
        ]);
    }
}
