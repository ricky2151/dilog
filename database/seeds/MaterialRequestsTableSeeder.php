<?php

use Illuminate\Database\Seeder;
use App\Models\MaterialRequest;

class MaterialRequestsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MaterialRequest::create([
            "code" => "MR-1",
            "division_id" => 1,
            "request_by_user_id" => 1,
            "periode_id"=> 1
        ]);

        MaterialRequest::create([
            "code" => "MR-2",
            "division_id" => 2,
            "request_by_user_id" => 2,
            "periode_id"=> 2
        ]);

        MaterialRequest::create([
            "code" => "MR-3",
            "division_id" => 3,
            "request_by_user_id" => 3,
            "periode_id"=> 3
        ]);

        MaterialRequest::create([
            "code" => "MR-4",
            "division_id" => 4,
            "request_by_user_id" => 4,
            "periode_id"=> 4
        ]);
    }
}
