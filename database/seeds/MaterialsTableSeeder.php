<?php

use App\Models\Material;
use Illuminate\Database\Seeder;

class MaterialsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Material::create([
            "name" => "Material 1",
            "goods_id" => 1,
            "total" => 100,
            "adjust" => "1 meter"
        ]);
        Material::create([
            "name" => "Material 2",
            "goods_id" => 2,
            "total" => 200,
            "adjust" => "1 meter"
        ]);
        Material::create([
            "name" => "Material 3",
            "goods_id" => 3,
            "total" => 300,
            "adjust" => "1 meter"
        ]);
        Material::create([
            "name" => "Material 4",
            "goods_id" => 4,
            "total" => 400,
            "adjust" => "1 meter"
        ]);
    }
}
