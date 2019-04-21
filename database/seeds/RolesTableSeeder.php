<?php

use Illuminate\Database\Seeder;
use App\Models\Role;
use Faker\Factory as Faker;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        Role::create([
            'name' => "Admin",
            'desc' => $faker->text(20)
        ]);
        Role::create([
            'name' => "Kasir",
            'desc' => $faker->text(20)
        ]);
        Role::create([
            'name' => "HRD",
            'desc' => $faker->text(20)
        ]);
        Role::create([
            'name' => "Manager",
            'desc' => $faker->text(20)
        ]);
        //
    }
}
