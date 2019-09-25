<?php

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SuppliersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1; $i < 10; $i++) {
            Supplier::create([
                "name_company" => "Company " . $i,
                "name_owner" => "Owner " . $i,
                "name_pic" => "Pic " . $i,
                "name_sales" => "Sales " . $i,
                "address" => "Address " . $i,
                "no_telp_company" => "telp company " . $i,
                "no_telp_owner" => "temp owner " . $i,
                "email" => "sup" . $i . "@email.com",
                "fax" => "fax " . $i,
                "npwp" => "npwp " . $i,
                "no_rek" => "rekening " . $i,
            ]);
        }
    }
}
