<?php

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'name' => "Botol"
        ]);
        Category::create([
            'name' => "Snack"
        ]);
        Category::create([
            'name' => "Cemilan"
        ]);
        Category::create([
            'name' => "Buku"
        ]);
    }
}
