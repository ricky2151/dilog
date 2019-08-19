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
        for ($i=1; $i < 100; $i++) {
            $random = rand(1,4);
            MaterialRequest::create([
                "no_mr" => "MR-" . $i,
                "division_id" => $random,
                "request_by_user_id" => $random,
                "periode_id"=> rand(1,4)
            ]);
        }
    }
}
