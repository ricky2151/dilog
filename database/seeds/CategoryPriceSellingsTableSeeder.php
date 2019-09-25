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
            'discount' => "10"
        ]);
        CategoryPriceSelling::create([
            'name' => "Category Price Selling 2",
            'discount' => "20"
        ]);
        CategoryPriceSelling::create([
            'name' => "Category Price Selling 3",
            'discount' => "30"
        ]);
        CategoryPriceSelling::create([
            'name' => "Category Price Selling 4",
            'discount' => "40"
        ]);
    }
}
