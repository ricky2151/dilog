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
        factory("App\Models\Role",4)->create();
        factory("App\Models\Warehouse",4)->create();
        // $this->call(UsersTableSeeder::class);
    }
}
