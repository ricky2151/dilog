<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory("App\Models\User",30)->create();
        // factory("App\Models\Role",4)->create();
        // factory("App\Models\Warehouse",4)->create();
        // factory("App\Models\Rack",4)->create();
        $this->call([
            RolesTableSeeder::class,
            WarehousesTableSeeder::class,
            RacksTableSeeder::class,
            CategoriesTableSeeder::class,
            CategoryGoodsTableSeeder::class,
            UnitTableSeeder::class,
            GoodsTableSeeder::class,
            AttributeGoodsTableSeeder::class,
            AttributesTableSeeder::class,
            TypesTableSeeder::class,
            CogsTableSeeder::class,
            CogsComponentsTableSeeder::class,
            SourcesTableSeeder::class,
            CategoryPriceSellingsTableSeeder::class,
            MaterialsTableSeeder::class,
            PriceSellingsTableSeeder::class,
            GoodsRacksTableSeeder::class,
            BatchsTableSeeder::class,
            SuppliersTableSeeder::class,
            PricelistsTableSeeder::class,
            DivisionsTableSeeder::class,
            PeriodeTableSeeder::class,
            MaterialRequestsTableSeeder::class,
            MaterialRequestDetailsTableSeeder::class
        ]);
    }
}
