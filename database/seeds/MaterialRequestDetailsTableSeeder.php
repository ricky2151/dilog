<?php

use Illuminate\Database\Seeder;
use App\Models\MaterialRequestDetail;

class MaterialRequestDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MaterialRequestDetail::create([
            "material_request_id" => 1,
            "goods_id" => 1,
            "qty" => 1,
            "notes"=> "barang keperluan 1",
        ]);

        MaterialRequestDetail::create([
            "material_request_id" => 2,
            "goods_id" => 2,
            "qty" => 2,
            "notes"=> "barang keperluan 2",
        ]);

        MaterialRequestDetail::create([
            "material_request_id" => 3,
            "goods_id" => 3,
            "qty" => 3,
            "notes"=> "barang keperluan 3",
        ]);

        MaterialRequestDetail::create([
            "material_request_id" => 4,
            "goods_id" => 4,
            "qty" => 4,
            "notes"=> "barang keperluan 4",
        ]);
    }
}
