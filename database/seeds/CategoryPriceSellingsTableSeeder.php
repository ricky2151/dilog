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
            'name' => "Category Price Selling 1"
        ]);
        CategoryPriceSelling::create([
            'name' => "Category Price Selling 2"
        ]);
        CategoryPriceSelling::create([
            'name' => "Category Price Selling 3"
        ]);
        CategoryPriceSelling::create([
            'name' => "Category Price Selling 4"
        ]);
    }
}
