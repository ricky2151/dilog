<?php

use App\Models\CategoryPriceSelling;
use Illuminate\Database\Seeder;

class CategoryPriceSellingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CategoryPriceSelling::create([
            'name' => "Category Price Selling 1",
            'diskon' => "10"
        ]);
        CategoryPriceSelling::create([
            'name' => "Category Price Selling 2",
            'diskon' => "20"
        ]);
        CategoryPriceSelling::create([
            'name' => "Category Price Selling 3",
            'diskon' => "30"
        ]);
        CategoryPriceSelling::create([
            'name' => "Category Price Selling 4",
            'diskon' => "40"
        ]);
    }
}
