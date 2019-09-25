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
        for ($i=0; $i < 100; $i++) {
            Material::create([
                "name" => "Material 1",
                "goods_id" => rand(1,4),
                "total" => rand(100, 200),
                "adjust" => "1 meter"
            ]);
        }
    }
}
