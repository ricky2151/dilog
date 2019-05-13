<?php

use App\Models\Source;
use Illuminate\Database\Seeder;

class SourcesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Source::create([
            'name' => "Source 1"
        ]);
        Source::create([
            'name' => "Source 2"
        ]);
        Source::create([
            'name' => "Source 3"
        ]);
        Source::create([
            'name' => "Source 4"
        ]);
    }
}
